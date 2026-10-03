<?php
declare(strict_types=1);

function envRequired(string $name): string {
    $value = getenv($name);
    if ($value === false || trim($value) === '') {
        throw new RuntimeException("Missing environment variable: {$name}");
    }
    return $value;
}
function pdo(string $prefix): PDO {
    $host = envRequired($prefix.'_HOST');
    $port = getenv($prefix.'_PORT') ?: '3306';
    $db = envRequired($prefix.'_DB');
    $user = envRequired($prefix.'_USER');
    $pass = getenv($prefix.'_PASS') ?: '';
    return new PDO("mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
}
function assertSafe(string $sourceDb, string $targetDb): void {
    if ($sourceDb === $targetDb) throw new RuntimeException('Source and target databases must differ.');
    if (getenv('FR7_ALLOW_LOCAL_MIGRATION') !== 'YES') {
        throw new RuntimeException('Set FR7_ALLOW_LOCAL_MIGRATION=YES explicitly.');
    }
    $blocked = ['production','prod','fletesram'];
    foreach ($blocked as $word) {
        if (stripos($targetDb, $word) !== false) throw new RuntimeException('Target database name looks unsafe.');
    }
}
function uuidv4(): string {
    $d = random_bytes(16); $d[6] = chr((ord($d[6]) & 0x0f) | 0x40); $d[8] = chr((ord($d[8]) & 0x3f) | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($d), 4));
}
function cleanDate($value): ?string {
    if (!$value || strncmp((string)$value, '0000-00-00', 10) === 0) return null;
    return (string)$value;
}

$sourceDb = envRequired('FR7_SOURCE_DB');
$targetDb = envRequired('FR7_TARGET_DB');
assertSafe($sourceDb, $targetDb);
$source = pdo('FR7_SOURCE');
$target = pdo('FR7_TARGET');

$bootstrap = file_get_contents(__DIR__.'/001_bootstrap.sql');
if ($bootstrap === false) throw new RuntimeException('Cannot read bootstrap SQL.');
$target->exec($bootstrap);

$count = (int)$source->query('SELECT COUNT(*) FROM ram_tipos_de_unidades')->fetchColumn();
$maxId = (string)($source->query('SELECT COALESCE(MAX(id),0) FROM ram_tipos_de_unidades')->fetchColumn());
$fingerprint = hash('sha256', $sourceDb.'|ram_tipos_de_unidades|'.$count.'|'.$maxId);
$uuid = uuidv4();

$target->prepare('INSERT INTO migration_runs (uuid,source_fingerprint,source_label,started_at,status,migrator_version) VALUES (?,?,?,NOW(),?,?)')
    ->execute([$uuid,$fingerprint,$sourceDb,'running','fr7-v0.1']);
$runId = (int)$target->lastInsertId();

try {
    $rows = $source->query('SELECT id,tipo_de_unidad,porcentaje,observaciones,deleted_at,created_at,updated_at FROM ram_tipos_de_unidades ORDER BY id')->fetchAll();
    $insert = $target->prepare('INSERT INTO vehicle_types (legacy_id,name,percentage,notes,is_active,created_at,updated_at) VALUES (?,?,?,?,?,?,?)');
    $map = $target->prepare('INSERT INTO migration_id_map (migration_run_id,domain,source_table,source_id,target_table,target_id,action) VALUES (?,?,?,?,?,?,?)');
    $target->beginTransaction();
    foreach ($rows as $row) {
        $insert->execute([(int)$row['id'],trim((string)$row['tipo_de_unidad']),$row['porcentaje'],(string)$row['observaciones'],$row['deleted_at'] === null ? 1 : 0,cleanDate($row['created_at']),cleanDate($row['updated_at'])]);
        $targetId = (string)$target->lastInsertId();
        $map->execute([$runId,'operation','ram_tipos_de_unidades',(string)$row['id'],'vehicle_types',$targetId,'inserted']);
    }
    $target->commit();

    $targetCount = (int)$target->query('SELECT COUNT(*) FROM vehicle_types')->fetchColumn();
    $mappedCount = (int)$target->query('SELECT COUNT(*) FROM migration_id_map WHERE migration_run_id='.(int)$runId." AND source_table='ram_tipos_de_unidades'")->fetchColumn();
    $passed = $targetCount === $count && $mappedCount === $count;
    $metrics = json_encode(['vehicle_types'=>['source'=>$count,'target'=>$targetCount,'mapped'=>$mappedCount]], JSON_UNESCAPED_SLASHES);
    $status = $passed ? 'passed' : 'failed';
    $target->prepare('UPDATE migration_runs SET finished_at=NOW(),status=?,metrics=? WHERE id=?')->execute([$status,$metrics,$runId]);
    if (!$passed) throw new RuntimeException('Reconciliation failed.');
    echo "FR-7 run {$uuid}: PASSED; vehicle_types={$targetCount}".PHP_EOL;
} catch (Throwable $e) {
    if ($target->inTransaction()) $target->rollBack();
    $target->prepare("UPDATE migration_runs SET finished_at=NOW(),status='failed',notes=? WHERE id=?")->execute([$e->getMessage(),$runId]);
    fwrite(STDERR, "FR-7 run {$uuid}: FAILED - {$e->getMessage()}".PHP_EOL);
    exit(1);
}
