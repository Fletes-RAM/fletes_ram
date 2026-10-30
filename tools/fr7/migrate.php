<?php
declare(strict_types=1);

function envRequired(string $name): string {
    $value = getenv($name);
    if ($value === false || trim($value) === '') throw new RuntimeException('CONFIG_MISSING');
    return $value;
}
function connectionConfig(string $prefix): array {
    $host = envRequired($prefix.'_HOST');
    $db = envRequired($prefix.'_DB');
    $port = getenv($prefix.'_PORT') ?: '3306';
    // TCP loopback only; no arbitrary DSN fragments, DNS names or remote endpoints.
    if ($host !== '127.0.0.1' || !preg_match('/^[0-9]+$/', $port) || (int)$port < 1 || (int)$port > 65535) {
        throw new RuntimeException('LOCAL_TCP_REQUIRED');
    }
    $pattern = $prefix === 'FR7_SOURCE' ? '/^(fletes_ram_local|fr7_source_test)$/' : '/^fr7_[a-z0-9_]+_test$/';
    if (!preg_match($pattern, $db)) throw new RuntimeException('SCHEMA_NOT_ALLOWED');
    return [$host, $port, $db];
}
function connectDatabase(string $prefix): PDO {
    [$host, $port, $db] = connectionConfig($prefix);
    return new PDO("mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4", envRequired($prefix.'_USER'), getenv($prefix.'_PASS') ?: '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_MULTI_STATEMENTS => false,
    ]);
}
function identity(PDO $pdo): array {
    $server = $pdo->query('SELECT @@hostname AS hostname, @@datadir AS datadir, @@server_id AS server_id, @@port AS port, @@version AS version, DATABASE() AS db')->fetch();
    $db = $server['db']; unset($server['db']);
    return ['server_identity'=>hash('sha256', json_encode($server, JSON_THROW_ON_ERROR)), 'db'=>$db];
}
function uuidv4(): string {
    $d = random_bytes(16); $d[6] = chr((ord($d[6]) & 15) | 64); $d[8] = chr((ord($d[8]) & 63) | 128);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($d), 4));
}
function cleanDate($value): ?string {
    if ($value === null || $value === '') return null;
    $date = DateTimeImmutable::createFromFormat('!Y-m-d H:i:s', (string)$value, new DateTimeZone('UTC'));
    $errors = DateTimeImmutable::getLastErrors();
    if (!$date || ($errors !== false && ($errors['warning_count'] || $errors['error_count'])) || $date->format('Y-m-d H:i:s') !== $value || (int)$date->format('Y') < 1000) return null;
    return $value;
}
function transformVehicleType(array $row): array {
    if (!preg_match('/^[1-9][0-9]*$/', (string)$row['id'])) throw new RuntimeException('INVALID_SOURCE_ID');
    if (!preg_match('/^-?[0-9]+$/', (string)$row['porcentaje']) || abs((int)$row['porcentaje']) > 99999) throw new RuntimeException('INVALID_PERCENTAGE');
    $exceptions = [];
    $dates = [];
    foreach (['created_at','updated_at','deleted_at'] as $field) {
        $dates[$field] = cleanDate($row[$field]);
        if ($row[$field] !== null && $dates[$field] === null) $exceptions[] = $field;
    }
    return [[
        'legacy_id'=>(string)$row['id'], 'name'=>$row['tipo_de_unidad'],
        'percentage'=>sprintf('%d.00', (int)$row['porcentaje']), 'notes'=>$row['observaciones'],
        'is_active'=>$row['deleted_at'] === null ? '1' : '0',
        'created_at'=>$dates['created_at'], 'updated_at'=>$dates['updated_at'],
    ], $exceptions];
}
function fingerprint(array $rows): string {
    $ctx = hash_init('sha256');
    hash_update($ctx, 'fr7:ram_tipos_de_unidades:v2:');
    foreach ($rows as $row) {
        $encoded = json_encode($row, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
        hash_update($ctx, strlen($encoded).':'.$encoded);
    }
    return hash_final($ctx);
}
function reconcile(array $expected, array $actual, array $maps): bool {
    if (count($expected) !== count($actual) || count($maps) !== count($expected)) return false;
    $byLegacy = []; $mapped = [];
    foreach ($actual as $row) {
        $id = (string)$row['legacy_id'];
        if (isset($byLegacy[$id])) return false;
        $byLegacy[$id] = $row;
    }
    foreach ($maps as $map) {
        $id = (string)$map['source_id'];
        if (isset($mapped[$id]) || !isset($byLegacy[$id]) || (string)$map['target_id'] !== (string)$byLegacy[$id]['id']) return false;
        $mapped[$id] = true;
    }
    foreach ($expected as $row) {
        $id = $row['legacy_id'];
        if (!isset($byLegacy[$id], $mapped[$id])) return false;
        foreach ($row as $key=>$value) {
            $got = $byLegacy[$id][$key];
            if ($value === null ? $got !== null : ($got === null || (string)$got !== (string)$value)) return false;
        }
    }
    return true;
}
function main(array $argv): int {
    $source = $target = null; $runId = null; $locked = false; $stage = 'preflight';
    try {
        if (count($argv) !== 2 || !in_array($argv[1], ['--inspect','--dry-run','--execute'], true)) throw new RuntimeException('MODE_REQUIRED');
        $mode = $argv[1];
        if (getenv('FR7_ALLOW_LOCAL_MIGRATION') !== 'YES') throw new RuntimeException('LOCAL_OPT_IN_REQUIRED');
        $source = connectDatabase('FR7_SOURCE'); $target = connectDatabase('FR7_TARGET');
        $sourceIdentity = identity($source); $targetIdentity = identity($target);
        if ($sourceIdentity === $targetIdentity) throw new RuntimeException('SAME_DATABASE');
        if ($mode === '--inspect') {
            echo json_encode(['source'=>$sourceIdentity,'target'=>$targetIdentity], JSON_THROW_ON_ERROR).PHP_EOL;
            return 0;
        }
        foreach (['FR7_SOURCE'=>$sourceIdentity,'FR7_TARGET'=>$targetIdentity] as $prefix=>$info) {
            if (!hash_equals(envRequired($prefix.'_SERVER_IDENTITY'), (string)$info['server_identity']) || $info['db'] !== envRequired($prefix.'_DB')) throw new RuntimeException('IDENTITY_MISMATCH');
        }
        $source->exec("SET time_zone = '+00:00'"); $target->exec("SET time_zone = '+00:00'");
        $target->exec("SET SESSION sql_mode='STRICT_ALL_TABLES,NO_ZERO_DATE,NO_ZERO_IN_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");
        // A consistent snapshot is meaningful only for transactional source tables.
        $engine = $source->query("SELECT ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='ram_tipos_de_unidades'")->fetchColumn();
        if ($engine !== 'InnoDB') throw new RuntimeException('SOURCE_ENGINE_UNSUPPORTED');
        $source->exec('SET TRANSACTION ISOLATION LEVEL REPEATABLE READ');
        $source->exec('SET TRANSACTION READ ONLY'); $source->beginTransaction();
        $rows = $source->query('SELECT id,tipo_de_unidad,porcentaje,observaciones,deleted_at,created_at,updated_at FROM ram_tipos_de_unidades ORDER BY id')->fetchAll();
        $expected = []; $exceptions = []; $transformed = [];
        foreach ($rows as $row) {
            [$values,$fields] = transformVehicleType($row); $expected[] = $values;
            $transformed[(string)$row['id']] = $fields !== [];
            foreach ($fields as $field) $exceptions[] = ['source_id'=>(string)$row['id'],'field'=>$field];
        }
        $hash = fingerprint($rows);
        if ($mode === '--dry-run') {
            $source->commit();
            echo json_encode(['mode'=>'dry-run','source'=>count($rows),'exceptions'=>count($exceptions),'fingerprint'=>$hash], JSON_THROW_ON_ERROR).PHP_EOL;
            return 0;
        }
        $stage = 'target_preflight';
        $lock = $target->prepare('SELECT GET_LOCK(?,0)'); $lock->execute(['fr7:'.$targetIdentity['db']]);
        if ((int)$lock->fetchColumn() !== 1) throw new RuntimeException('TARGET_BUSY');
        $locked = true;
        if ((int)$target->query('SELECT COUNT(*) FROM information_schema.TRIGGERS WHERE TRIGGER_SCHEMA=DATABASE()')->fetchColumn() !== 0) throw new RuntimeException('TARGET_TRIGGERS_FORBIDDEN');
        $tables = $target->query('SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE()')->fetchAll(PDO::FETCH_COLUMN);
        $allowed = ['migration_runs','migration_id_map','migration_exceptions','vehicle_types'];
        foreach ($tables as $table) {
            if (!in_array($table, $allowed, true)) throw new RuntimeException('UNEXPECTED_TARGET_TABLE');
            if ($table !== 'migration_runs' && (int)$target->query('SELECT COUNT(*) FROM `'.$table.'`')->fetchColumn() !== 0) throw new RuntimeException('TARGET_NOT_EMPTY');
        }
        $stage = 'bootstrap';
        $sql = file_get_contents(__DIR__.'/001_bootstrap.sql');
        if ($sql === false) throw new RuntimeException('BOOTSTRAP_UNREADABLE');
        // This controlled file contains simple CREATE TABLE statements only.
        foreach (explode(';', $sql) as $statement) if (trim($statement) !== '') $target->exec($statement);
        foreach ($allowed as $table) {
            $q = $target->prepare('SELECT ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=?'); $q->execute([$table]);
            if ($q->fetchColumn() !== 'InnoDB') throw new RuntimeException('TARGET_ENGINE_UNSUPPORTED');
        }
        $uuid = uuidv4();
        $target->prepare('INSERT INTO migration_runs (uuid,source_fingerprint,source_label,started_at,status,migrator_version) VALUES (?,?,?,NOW(),?,?)')->execute([$uuid,$hash,'local-vehicle-types','running','fr7-v0.2']);
        $runId = (string)$target->lastInsertId();
        $stage = 'migration'; $target->beginTransaction();
        $insert = $target->prepare('INSERT INTO vehicle_types (legacy_id,name,percentage,notes,is_active,created_at,updated_at) VALUES (?,?,?,?,?,?,?)');
        $map = $target->prepare("INSERT INTO migration_id_map (migration_run_id,domain,source_table,source_id,target_table,target_id,action) VALUES (?,'operation','ram_tipos_de_unidades',?,'vehicle_types',?,?)");
        foreach ($expected as $values) {
            $insert->execute(array_values($values)); $map->execute([$runId,$values['legacy_id'],(string)$target->lastInsertId(),$transformed[$values['legacy_id']] ? 'transformed' : 'inserted']);
        }
        $exception = $target->prepare("INSERT INTO migration_exceptions (migration_run_id,domain,source_table,source_id,code,severity,resolution,status,target_table,target_id) SELECT ?,'operation','ram_tipos_de_unidades',?,'INVALID_DATE_TO_NULL','warning',?,'transformed','vehicle_types',id FROM vehicle_types WHERE legacy_id=?");
        foreach ($exceptions as $item) $exception->execute([$runId,$item['source_id'],($item['field'] === 'deleted_at' ? 'Invalid deleted_at; original non-null deletion marker preserved as inactive' : $item['field'].' converted to NULL'),$item['source_id']]);
        $stage = 'reconciliation';
        $actual = $target->query('SELECT * FROM vehicle_types ORDER BY legacy_id')->fetchAll();
        $q = $target->prepare("SELECT source_id,target_id FROM migration_id_map WHERE migration_run_id=? AND source_table='ram_tipos_de_unidades' AND target_table='vehicle_types'"); $q->execute([$runId]);
        if (!reconcile($expected,$actual,$q->fetchAll())) throw new RuntimeException('CONTENT_RECONCILIATION_FAILED');
        $metrics = json_encode(['vehicle_types'=>['source'=>count($rows),'target'=>count($actual),'mapped'=>count($actual),'exceptions'=>count($exceptions),'content_equal'=>true]], JSON_THROW_ON_ERROR);
        $source->commit();
        $target->prepare("UPDATE migration_runs SET finished_at=NOW(),status='passed',metrics=? WHERE id=?")->execute([$metrics,$runId]);
        $target->commit();
        echo "FR-7 run {$uuid}: PASSED; vehicle_types=".count($actual).PHP_EOL;
        return 0;
    } catch (Throwable $e) {
        // SQL/driver messages may contain source data or credentials: never persist them.
        $cleanupFailed = false;
        foreach ([$source,$target] as $pdo) {
            try { if ($pdo instanceof PDO && $pdo->inTransaction()) $pdo->rollBack(); }
            catch (Throwable $ignored) { $cleanupFailed = true; }
        }
        if ($runId !== null) {
            try { $target->prepare("UPDATE migration_runs SET finished_at=NOW(),status='failed',notes=? WHERE id=?")->execute(['FAILED_STAGE:'.$stage,$runId]); }
            catch (Throwable $ignored) { $cleanupFailed = true; }
        }
        fwrite(STDERR, 'FR-7 FAILED; stage='.$stage.($cleanupFailed ? '; AUDIT_OR_ROLLBACK_FAILED' : '').PHP_EOL);
        return 1;
    } finally {
        if ($locked) {
            try { $target->query('SELECT RELEASE_LOCK('.$target->quote('fr7:'.envRequired('FR7_TARGET_DB')).')'); }
            catch (Throwable $ignored) { fwrite(STDERR, "FR-7 LOCK_RELEASE_FAILED; connection will close".PHP_EOL); }
        }
    }
}
if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) exit(main($argv));
