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
function mappingIdCollation(PDO $target):string {
    $collation=$target->query("SELECT COLLATION_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='migration_id_map' AND COLUMN_NAME='source_id'")->fetchColumn();
    if(!is_string($collation)||!preg_match('/^utf8mb4_[a-z0-9_]+$/D',$collation))throw new RuntimeException('UNSUPPORTED_MAPPING_COLLATION');
    return $collation;
}
function reconcileStoredDomain(PDO $target,iterable $expected,string $table,array $definition,string $runId,?array &$diagnostic=null,?int $expectedCount=null): bool {
    $diagnostic=['reason'=>'unknown'];
    $expectedCount ??= is_countable($expected)?count($expected):throw new RuntimeException('EXPECTED_COUNT_REQUIRED');
    $dest=$definition['target'];$scope=fuelScope($definition);
    $q=$target->prepare('SELECT COUNT(*) FROM migration_id_map WHERE migration_run_id=? AND source_table=? AND target_table=?');$q->execute([$runId,$table,$dest]);
    if((int)$q->fetchColumn()!==$expectedCount){$diagnostic=['reason'=>'mapping_count'];return false;}
    $where=$scope==='' ? '' : str_replace('legacy_source','a.legacy_source',$scope);
    $columnCollation=mappingIdCollation($target);

    // IDs are canonical ASCII digits. Compare bytes, independent of server/connection collations.
    $sql='SELECT a.*,m.source_id AS __map_source,m.target_id AS __map_target FROM `'.$dest.'` a LEFT JOIN migration_id_map m ON m.migration_run_id=? AND m.source_table=? AND m.target_table=? AND m.source_id=CAST(a.legacy_id AS CHAR CHARACTER SET utf8mb4) COLLATE '.$columnCollation.' AND CAST(m.source_id AS BINARY)=CAST(a.legacy_id AS BINARY)'.$where.' ORDER BY a.legacy_id';
    $target->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,false);
    $cursor=null;
    try {
        $cursor=$target->prepare($sql);$cursor->execute([$runId,$table,$dest]);
        foreach($expected as $row){
            $actual=$cursor->fetch();
            if($actual===false){$diagnostic=['reason'=>'missing_target_row'];return false;}
            if((string)$actual['__map_source']!==(string)$row['legacy_id']){$diagnostic=['reason'=>'source_mapping'];return false;}
            if((string)$actual['__map_target']!==(string)$actual['id']){$diagnostic=['reason'=>'target_mapping'];return false;}
            foreach($row as $column=>$value){
                $got=$actual[$column];
                if($value===null ? $got!==null : ($got===null || (string)$got!==(string)$value)){$diagnostic=['reason'=>'field_mismatch','column'=>$column];return false;}
            }
        }
        if($cursor->fetch()!==false){$diagnostic=['reason'=>'extra_target_row'];return false;}
        $diagnostic=['reason'=>'equal'];return true;
    } finally {
        if($cursor!==null)$cursor->closeCursor();
        $target->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
    }
}

require_once __DIR__.'/core.php';
require_once __DIR__.'/operations.php';
require_once __DIR__.'/fuel.php';
require_once __DIR__.'/billing.php';
require_once __DIR__.'/streaming.php';
require_once __DIR__.'/payables.php';
require_once __DIR__.'/banking.php';

function main(array $argv): int {
    if(count($argv)===3&&$argv[2]==='--banking')return billingMain([$argv[0],$argv[1]],'banking');
    if(count($argv)===3&&$argv[2]==='--payables')return billingMain([$argv[0],$argv[1]],'payables');
    if(count($argv)===3&&$argv[2]==='--billing')return billingMain([$argv[0],$argv[1]]);
    $source = $target = null; $runId = null; $locked = false; $stage = 'preflight'; $reconciliationDiagnostic=null;
    try {
        $fuel = count($argv) === 3 && $argv[2] === '--fuel';
        $operations = $fuel || (count($argv) === 3 && $argv[2] === '--operations');
        $core = $operations || (count($argv) === 3 && $argv[2] === '--core');
        if ($core) array_pop($argv);
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
        $definitions = $fuel ? fuelDefinitions() : ($operations ? operationDefinitions() : ($core ? coreDefinitions() : ['ram_tipos_de_unidades'=>['target'=>'vehicle_types']]));
        $sourceTables = array_keys($definitions);
        if ($core) $sourceTables[]='ram_users'; // identity references only, never credentials
        foreach ($sourceTables as $table) {
            $q=$source->prepare('SELECT ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=?'); $q->execute([$table]);
            if ($q->fetchColumn() !== 'InnoDB') throw new RuntimeException('SOURCE_ENGINE_UNSUPPORTED');
        }
        $source->exec('SET TRANSACTION ISOLATION LEVEL REPEATABLE READ');
        $source->exec('SET TRANSACTION READ ONLY'); $source->beginTransaction();
        $allRows=[]; $parents=[]; $expectedByTable=[]; $exceptions=[]; $transformed=[];
        foreach ($sourceTables as $table) {
            $columns=$table==='ram_users' ? 'id' : '*';
            if ($table==='ram_unidades') $columns .= ',CAST(km_inicial AS CHAR) AS km_inicial';
            foreach ($definitions[$table]['money']??[] as $column=>$unused) $columns .= ',CAST(`'.$column.'` AS CHAR) AS `'.$column.'`';
            $allRows[$table]=$source->query('SELECT '.$columns.' FROM `'.$table.'` ORDER BY id')->fetchAll();
            $parents[$table]=array_fill_keys(array_map(fn($r)=>(string)$r['id'],$allRows[$table]),true);
        }
        foreach ($definitions as $table=>$definition) {
            // An unreviewed schema change must not silently drop legacy columns.
            if ($core) {
                $q=$source->prepare('SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=?');$q->execute([$table]);
                $columns=$q->fetchAll(PDO::FETCH_COLUMN);$declared=declaredSourceColumns($table,$definition);
                if (array_diff($columns,$declared)!==[] || array_diff($declared,$columns)!==[]) throw new RuntimeException('SOURCE_SCHEMA_UNREVIEWED');
            }
            $expectedByTable[$table]=[];
            foreach ($allRows[$table] as $row) {
                if ($fuel) [$values,$items]=fuelTransform($table,$row,$parents);
                elseif ($operations) [$values,$items]=operationTransform($table,$row,$parents);
                elseif ($core) [$values,$items]=coreTransform($table,$row,$parents);
                else { [$values,$fields]=transformVehicleType($row); $items=array_map(fn($f)=>['code'=>'INVALID_DATE_TO_NULL','field'=>$f],$fields); }
                $expectedByTable[$table][]=$values;
                $transformed[$table][(string)$row['id']]=$items!==[];
                foreach ($items as $item) $exceptions[]=$item+['source_table'=>$table,'target_table'=>$definition['target'],'source_id'=>(string)$row['id']];
            }
        }
        $hash=$core ? coreFingerprint($allRows) : fingerprint($allRows['ram_tipos_de_unidades']);
        $totalRows=array_sum(array_map('count',$expectedByTable));
        $sourceCounts=array_map('count',$allRows);
        unset($allRows); // rows have been fingerprinted and transformed; do not retain a second copy
        if ($mode === '--dry-run') {
            $source->commit();
            echo json_encode(['mode'=>'dry-run','source'=>$totalRows,'exceptions'=>count($exceptions),'fingerprint'=>$hash], JSON_THROW_ON_ERROR).PHP_EOL;
            return 0;
        }
        $stage = 'target_preflight';
        $lock = $target->prepare('SELECT GET_LOCK(?,0)'); $lock->execute(['fr7:'.$targetIdentity['db']]);
        if ((int)$lock->fetchColumn() !== 1) throw new RuntimeException('TARGET_BUSY');
        $locked = true;
        if ((int)$target->query('SELECT COUNT(*) FROM information_schema.TRIGGERS WHERE TRIGGER_SCHEMA=DATABASE()')->fetchColumn() !== 0) throw new RuntimeException('TARGET_TRIGGERS_FORBIDDEN');
        $tables = $target->query('SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE()')->fetchAll(PDO::FETCH_COLUMN);
        $allowed = array_merge(['migration_runs','migration_id_map','migration_exceptions'],array_values(array_unique(array_column($definitions,'target'))));
        foreach ($tables as $table) {
            if (!in_array($table, $allowed, true)) throw new RuntimeException('UNEXPECTED_TARGET_TABLE');
            if ($table !== 'migration_runs' && (int)$target->query('SELECT COUNT(*) FROM `'.$table.'`')->fetchColumn() !== 0) throw new RuntimeException('TARGET_NOT_EMPTY');
        }
        $stage = 'bootstrap';
        $sql = file_get_contents(__DIR__.'/001_bootstrap.sql');
        if ($sql === false) throw new RuntimeException('BOOTSTRAP_UNREADABLE');
        if ($core) {
            $coreSql=file_get_contents(__DIR__.'/002_core.sql');
            if ($coreSql===false) throw new RuntimeException('BOOTSTRAP_UNREADABLE');
            $sql .= $coreSql;
            if ($operations) {
                $operationSql=file_get_contents(__DIR__.'/003_operations.sql');
                if ($operationSql===false) throw new RuntimeException('BOOTSTRAP_UNREADABLE');
                $sql .= $operationSql;
                if ($fuel) {
                    $fuelSql=file_get_contents(__DIR__.'/004_fuel.sql');
                    if ($fuelSql===false) throw new RuntimeException('BOOTSTRAP_UNREADABLE');
                    $sql .= $fuelSql;
                }
            }
        }
        // This controlled file contains simple CREATE TABLE statements only.
        foreach (explode(';', $sql) as $statement) if (trim($statement) !== '') $target->exec($statement);
        foreach ($allowed as $table) {
            $q = $target->prepare('SELECT ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=?'); $q->execute([$table]);
            if ($q->fetchColumn() !== 'InnoDB') throw new RuntimeException('TARGET_ENGINE_UNSUPPORTED');
        }
        $uuid = uuidv4();
        $target->prepare('INSERT INTO migration_runs (uuid,source_fingerprint,source_label,started_at,status,migrator_version) VALUES (?,?,?,NOW(),?,?)')->execute([$uuid,$hash,$fuel ? 'local-fuel' : ($operations ? 'local-operations' : ($core ? 'local-core' : 'local-vehicle-types')),'running',$fuel ? 'fr7-v0.5' : ($operations ? 'fr7-v0.4' : ($core ? 'fr7-v0.3' : 'fr7-v0.2'))]);
        $runId = (string)$target->lastInsertId();
        $stage = 'migration'; $target->beginTransaction();
        $map=$target->prepare("INSERT INTO migration_id_map (migration_run_id,domain,source_table,source_id,target_table,target_id,action) VALUES (?,'operation',?,?,?,?,?)");
        foreach ($definitions as $table=>$definition) {
            $dest=$definition['target'];
            foreach ($expectedByTable[$table] as $values) {
                $columns=array_keys($values);
                $insert=$target->prepare('INSERT INTO `'.$dest.'` (`'.implode('`,`',$columns).'`) VALUES ('.implode(',',array_fill(0,count($columns),'?')).')');
                $insert->execute(array_values($values));
                $targetId=$values['id'] ?? (string)$target->lastInsertId();
                $map->execute([$runId,$table,$values['legacy_id'],$dest,$targetId,$transformed[$table][$values['legacy_id']]?'transformed':'inserted']);
            }
        }
        $exception=$target->prepare("INSERT INTO migration_exceptions (migration_run_id,domain,source_table,source_id,code,severity,resolution,status,target_table,target_id) VALUES (?,'operation',?,?,?,'warning',?,?,?,?)");
        foreach ($exceptions as $item) {
            $scope=fuelScope($definitions[$item['source_table']]);
            $q=$target->prepare('SELECT id FROM `'.$item['target_table'].'`'.($scope===''?' WHERE ':$scope.' AND ').'legacy_id=?');$q->execute([$item['source_id']]);
            $preserved=in_array($item['code'],['DECIMAL_TEXT_PRESERVED','ORPHAN_REFERENCE_PRESERVED','ORPHAN_USER_REFERENCE','ZERO_LITERS_PRESERVED'],true);
            $exception->execute([$runId,$item['source_table'],$item['source_id'],$item['code'],$item['field'].': '.$item['code'],$preserved?'preserved':'transformed',$item['target_table'],$q->fetchColumn()]);
        }
        $stage = 'reconciliation'; $metrics=[];
        foreach ($definitions as $table=>$definition) {
            $dest=$definition['target'];$scope=fuelScope($definition);
            $stage='reconciliation:'.$dest;
            if (!reconcileStoredDomain($target,$expectedByTable[$table],$table,$definition,$runId,$reconciliationDiagnostic)) throw new RuntimeException('CONTENT_RECONCILIATION_FAILED');
            $verifiedCount=count($expectedByTable[$table]);
            $metricKey=isset($definition['scope']) ? $dest.'.'.$definition['scope'] : $dest;
            $metrics[$metricKey]=['source'=>$sourceCounts[$table],'target'=>$verifiedCount,'mapped'=>$verifiedCount,'exceptions'=>count(array_filter($exceptions,fn($e)=>$e['source_table']===$table)),'content_equal'=>true];
            foreach ($definition['money']??[] as $column=>$targetColumn) {
                $sourceTotal=$source->query('SELECT CAST(COALESCE(SUM(CAST(`'.$column.'` AS DECIMAL(30,2))),0) AS DECIMAL(40,2)) FROM `'.$table.'`')->fetchColumn();
                $targetTotal=$target->query('SELECT CAST(COALESCE(SUM(`'.$targetColumn.'`),0) AS DECIMAL(40,2)) FROM `'.$dest.'`'.$scope)->fetchColumn();
                if ((string)$sourceTotal!==(string)$targetTotal) throw new RuntimeException('AMOUNT_RECONCILIATION_FAILED');
                $metrics[$metricKey]['amount_totals'][$targetColumn]=(string)$targetTotal;
            }

        }
        unset($expectedByTable);
        // Verify the resolved parents exist in the actual destination, not only in source.
        if ($core) foreach (($fuel ? fuelRelations() : ($operations ? operationRelations() : [['vehicles','vehicle_type_id','vehicle_types'],['operators','vehicle_id','vehicles'],['operators','client_id','clients']])) as [$child,$column,$parent]) {
            if ((int)$target->query('SELECT COUNT(*) FROM `'.$child.'` c LEFT JOIN `'.$parent.'` p ON p.id=c.`'.$column.'` WHERE c.`'.$column.'` IS NOT NULL AND p.id IS NULL')->fetchColumn()!==0) throw new RuntimeException('RELATION_RECONCILIATION_FAILED');
        }
        $metrics=json_encode($metrics,JSON_THROW_ON_ERROR);
        $source->commit();
        $target->prepare("UPDATE migration_runs SET finished_at=NOW(),status='passed',metrics=? WHERE id=?")->execute([$metrics,$runId]);
        $target->commit();
        echo "FR-7 run {$uuid}: PASSED; rows=".$totalRows.PHP_EOL;
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
        $safeDetail='';
        if($e instanceof PDOException){
            $state=(string)($e->errorInfo[0]??$e->getCode());
            if(preg_match('/^[A-Z0-9]{5}$/D',$state))$safeDetail.='; SQLSTATE='.$state;
            if(isset($e->errorInfo[1])&&is_numeric($e->errorInfo[1]))$safeDetail.='; DRIVER_CODE='.(int)$e->errorInfo[1];
        } elseif(str_starts_with($stage,'reconciliation:')&&is_array($reconciliationDiagnostic)) {
            foreach($reconciliationDiagnostic as $key=>$value)if(preg_match('/^[a-z_]+$/D',(string)$value))$safeDetail.='; '.$key.'='.$value;
        }
        fwrite(STDERR, 'FR-7 FAILED; stage='.$stage.$safeDetail.($cleanupFailed ? '; AUDIT_OR_ROLLBACK_FAILED' : '').PHP_EOL);
        return 1;
    } finally {
        if ($locked) {
            try { $target->query('SELECT RELEASE_LOCK('.$target->quote('fr7:'.envRequired('FR7_TARGET_DB')).')'); }
            catch (Throwable $ignored) { fwrite(STDERR, "FR-7 LOCK_RELEASE_FAILED; connection will close".PHP_EOL); }
        }
    }
}
if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) exit(main($argv));
