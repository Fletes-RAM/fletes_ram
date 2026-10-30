<?php
declare(strict_types=1);
require __DIR__.'/../migrate.php';
if (getenv('FR7_TEST_CREATE_FIXTURES') !== 'YES') throw new RuntimeException('Explicit synthetic fixture opt-in required');
$port = getenv('FR7_TEST_PORT') ?: '3306';
if (!preg_match('/^[0-9]+$/',$port)) throw new RuntimeException('Invalid local port');
$admin = new PDO("mysql:host=127.0.0.1;port={$port};charset=utf8mb4", envRequired('FR7_TEST_USER'), getenv('FR7_TEST_PASS') ?: '', [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
$schemas = ['fr7_source_test','fr7_a_test','fr7_b_test','fr7_failure_test'];
foreach ($schemas as $db) {
    $q=$admin->prepare('SELECT COUNT(*) FROM information_schema.SCHEMATA WHERE SCHEMA_NAME=?'); $q->execute([$db]);
    if ((int)$q->fetchColumn() !== 0) throw new RuntimeException('Fixture schema already exists; use a fresh disposable server');
}
foreach ($schemas as $db) $admin->exec('CREATE DATABASE `'.$db.'`');
$admin->exec("SET SESSION sql_mode=''");
$admin->exec('CREATE TABLE fr7_source_test.ram_tipos_de_unidades (id INT UNSIGNED PRIMARY KEY, tipo_de_unidad VARCHAR(100) NOT NULL, porcentaje INT NOT NULL, observaciones TEXT NOT NULL, deleted_at DATETIME NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL) ENGINE=InnoDB');
$insert=$admin->prepare('INSERT INTO fr7_source_test.ram_tipos_de_unidades VALUES (?,?,?,?,?,?,?)');
$insert->execute([1,'Camión ñ',15,'Nota sintética',null,'2026-01-01 00:00:00','2026-01-02 00:00:00']);
$insert->execute([2,'  Tráiler  ',0,'Conservar espacios','2026-02-01 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00']);
$readerPass=bin2hex(random_bytes(16));
$admin->exec("CREATE USER 'fr7_fixture_reader'@'127.0.0.1' IDENTIFIED BY ".$admin->quote($readerPass));
$admin->exec("GRANT SELECT ON fr7_source_test.* TO 'fr7_fixture_reader'@'127.0.0.1'");
putenv('FR7_ALLOW_LOCAL_MIGRATION=YES');
foreach (['FR7_SOURCE','FR7_TARGET'] as $prefix) {
    putenv($prefix.'_HOST=127.0.0.1'); putenv($prefix.'_PORT='.$port);
    putenv($prefix.'_USER='.envRequired('FR7_TEST_USER')); putenv($prefix.'_PASS='.(getenv('FR7_TEST_PASS') ?: ''));
}
putenv('FR7_SOURCE_USER=fr7_fixture_reader'); putenv('FR7_SOURCE_PASS='.$readerPass);
putenv('FR7_SOURCE_DB=fr7_source_test'); putenv('FR7_TARGET_DB=fr7_a_test');
$server=identity(connectDatabase('FR7_SOURCE'))['server_identity'];
putenv('FR7_SOURCE_SERVER_IDENTITY='.$server); putenv('FR7_TARGET_SERVER_IDENTITY='.$server);
$count=0;
function verify(bool $ok,string $message): void { global $count; if (!$ok) throw new RuntimeException($message); $count++; }
function invoke(string $mode): array { ob_start(); $code=main(['migrate.php',$mode]); return [$code,ob_get_clean()]; }
verify(invoke('--inspect')[0]===0,'inspect');
verify(invoke('--dry-run')[0]===0,'dry-run');
verify((int)$admin->query("SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA='fr7_a_test'")->fetchColumn()===0,'dry-run no target writes');
putenv('FR7_TARGET_SERVER_IDENTITY=wrong'); verify(invoke('--execute')[0]===1,'wrong identity rejected');
verify((int)$admin->query("SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA='fr7_a_test'")->fetchColumn()===0,'identity failure no DDL');
putenv('FR7_TARGET_SERVER_IDENTITY='.$server);
$before=fingerprint(connectDatabase('FR7_SOURCE')->query('SELECT * FROM ram_tipos_de_unidades ORDER BY id')->fetchAll(PDO::FETCH_ASSOC));
foreach (['fr7_a_test','fr7_b_test'] as $db) {
    putenv('FR7_TARGET_DB='.$db);
    verify(invoke('--execute')[0]===0,'migration passed '.$db);
    verify($admin->query("SELECT status FROM {$db}.migration_runs")->fetchColumn()==='passed','audit passed');
    verify((int)$admin->query("SELECT COUNT(*) FROM {$db}.migration_exceptions")->fetchColumn()===2,'date exceptions persisted');
    verify((int)$admin->query("SELECT COUNT(*) FROM {$db}.migration_id_map")->fetchColumn()===2,'mappings persisted');
    verify(invoke('--execute')[0]===1,'populated target rejected');
    verify((int)$admin->query("SELECT COUNT(*) FROM {$db}.migration_runs")->fetchColumn()===1,'rerun did not add run');
}
$a=$admin->query('SELECT legacy_id,name,percentage,notes,is_active,created_at,updated_at FROM fr7_a_test.vehicle_types ORDER BY legacy_id')->fetchAll(PDO::FETCH_ASSOC);
$b=$admin->query('SELECT legacy_id,name,percentage,notes,is_active,created_at,updated_at FROM fr7_b_test.vehicle_types ORDER BY legacy_id')->fetchAll(PDO::FETCH_ASSOC);
verify($a===$b,'reproducible content');
verify($admin->query('SELECT source_fingerprint FROM fr7_a_test.migration_runs')->fetchColumn()===$admin->query('SELECT source_fingerprint FROM fr7_b_test.migration_runs')->fetchColumn(),'reproducible source fingerprint');
verify($a[1]['name']==='  Tráiler  ' && (int)$a[1]['is_active']===0,'verbatim name and deletion');
$after=fingerprint(connectDatabase('FR7_SOURCE')->query('SELECT * FROM ram_tipos_de_unidades ORDER BY id')->fetchAll(PDO::FETCH_ASSOC));
verify($before===$after,'source unchanged');
// Deliberately invalid synthetic row: strict target insertion fails after row one.
$admin->exec("UPDATE fr7_source_test.ram_tipos_de_unidades SET tipo_de_unidad=REPEAT('x',81), observaciones='PRIVATE_SYNTHETIC_MARKER' WHERE id=2");
putenv('FR7_TARGET_DB=fr7_failure_test');
verify(invoke('--execute')[0]===1,'failed insertion');
verify((int)$admin->query('SELECT COUNT(*) FROM fr7_failure_test.vehicle_types')->fetchColumn()===0,'business rollback');
verify((int)$admin->query('SELECT COUNT(*) FROM fr7_failure_test.migration_id_map')->fetchColumn()===0,'mapping rollback');
$run=$admin->query('SELECT status,notes FROM fr7_failure_test.migration_runs')->fetch(PDO::FETCH_ASSOC);
verify($run['status']==='failed' && $run['notes']==='FAILED_STAGE:migration','sanitized failure audit');
echo "INTEGRATION PASSED: {$count} checks; two reproducible synthetic runs; rollback verified".PHP_EOL;
