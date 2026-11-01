<?php
declare(strict_types=1);
require __DIR__.'/../migrate.php';
if(getenv('FR7_TEST_CREATE_FIXTURES')!=='YES')throw new RuntimeException('Explicit synthetic fixture opt-in required');
$port=getenv('FR7_TEST_PORT')?:'3306';if(!preg_match('/^[0-9]+$/D',$port))throw new RuntimeException('Invalid port');
$a=new PDO("mysql:host=127.0.0.1;port={$port};charset=utf8mb4",envRequired('FR7_TEST_USER'),getenv('FR7_TEST_PASS')?:'',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
$dbs=['fr7_source_test','fr7_core_a_test','fr7_core_b_test','fr7_core_failure_test'];
foreach($dbs as $db){$q=$a->prepare('SELECT COUNT(*) FROM information_schema.SCHEMATA WHERE SCHEMA_NAME=?');$q->execute([$db]);if((int)$q->fetchColumn()!==0)throw new RuntimeException('Fresh disposable server required');}
foreach($dbs as $db)$a->exec('CREATE DATABASE `'.$db.'`');
$a->exec('USE fr7_source_test');$a->exec("SET sql_mode=''");
$a->exec('CREATE TABLE ram_users (id INT UNSIGNED PRIMARY KEY) ENGINE=InnoDB');$a->exec('INSERT INTO ram_users VALUES (1)');
$a->exec('CREATE TABLE ram_clientes (id INT UNSIGNED PRIMARY KEY,cliente VARCHAR(201),nombre_contacto VARCHAR(200),email VARCHAR(200),telefono VARCHAR(15),gasto_admon VARCHAR(10),observaciones TEXT,deleted_at DATETIME NULL,created_at DATETIME,updated_at DATETIME) ENGINE=InnoDB');
$a->exec("INSERT INTO ram_clientes VALUES (8,'  Cliente sintético  ','C','test@example.invalid','000','15.25','nota',NULL,'2026-01-01 00:00:00','2026-01-01 00:00:00'),(30,'Baja','C','','','15%','','0000-00-00 00:00:00','0000-00-00 00:00:00','2026-01-01 00:00:00')");
$a->exec('CREATE TABLE ram_tipos_de_unidades (id INT UNSIGNED PRIMARY KEY,tipo_de_unidad VARCHAR(80),porcentaje INT,observaciones TEXT,deleted_at DATETIME NULL,created_at DATETIME,updated_at DATETIME) ENGINE=InnoDB');
$a->exec("INSERT INTO ram_tipos_de_unidades VALUES (7,'Tipo',15,'',NULL,'2026-01-01 00:00:00','2026-01-01 00:00:00')");
$a->exec('CREATE TABLE ram_unidades (id INT UNSIGNED PRIMARY KEY,unidad VARCHAR(15),tipo_de_unidad_id INT UNSIGNED,placas VARCHAR(20),serie VARCHAR(30),poliza VARCHAR(30),aseguradora VARCHAR(50),vigencia DATE,km_inicial DECIMAL(15,2),observaciones TEXT,deleted_at DATETIME NULL,created_at DATETIME,updated_at DATETIME) ENGINE=InnoDB');
$a->exec("INSERT INTO ram_unidades VALUES (9,'Unidad',7,'','','','','2026-01-01',12345.67,'',NULL,'2026-01-01 00:00:00','2026-01-01 00:00:00'),(12,'Huérfana',99,'','','','','0000-00-00',0,'',NULL,'2026-01-01 00:00:00','2026-01-01 00:00:00')");
$a->exec('CREATE TABLE ram_operadores (id INT UNSIGNED PRIMARY KEY,user_id INT UNSIGNED,nss VARCHAR(11),telefono VARCHAR(15),contacto VARCHAR(100),tel_contacto VARCHAR(15),licencia VARCHAR(255),vigencia DATE,medica DATE,unidad_id INT UNSIGNED,cliente_id INT UNSIGNED,origen VARCHAR(255),destino VARCHAR(255),estatus VARCHAR(255),observaciones TEXT,deleted_at DATETIME NULL,created_at DATETIME,updated_at DATETIME) ENGINE=InnoDB');
$a->exec("INSERT INTO ram_operadores VALUES (16,1,'','','','','','2026-01-01','2026-01-01',9,8,'','','activo','',NULL,'2026-01-01 00:00:00','2026-01-01 00:00:00'),(21,99,'','','','','','0000-00-00','2026-01-01',0,99,'','','legacy','',NULL,'2026-01-01 00:00:00','2026-01-01 00:00:00')");
$pass=bin2hex(random_bytes(16));$a->exec("CREATE USER 'fr7_core_reader'@'127.0.0.1' IDENTIFIED BY ".$a->quote($pass));$a->exec("GRANT SELECT ON fr7_source_test.* TO 'fr7_core_reader'@'127.0.0.1'");
putenv('FR7_ALLOW_LOCAL_MIGRATION=YES');foreach(['FR7_SOURCE','FR7_TARGET'] as $x){putenv($x.'_HOST=127.0.0.1');putenv($x.'_PORT='.$port);putenv($x.'_USER='.envRequired('FR7_TEST_USER'));putenv($x.'_PASS='.(getenv('FR7_TEST_PASS')?:''));}
putenv('FR7_SOURCE_DB=fr7_source_test');putenv('FR7_SOURCE_USER=fr7_core_reader');putenv('FR7_SOURCE_PASS='.$pass);putenv('FR7_TARGET_DB=fr7_core_a_test');
$reader=connectDatabase('FR7_SOURCE');$identity=identity($reader)['server_identity'];putenv('FR7_SOURCE_SERVER_IDENTITY='.$identity);putenv('FR7_TARGET_SERVER_IDENTITY='.$identity);
$count=0;
function coreCheck(bool $ok,string $m):void{global $count;if(!$ok)throw new RuntimeException($m);$count++;}
function invokeCore(string $mode):int{ob_start();$r=main(['migrate.php',$mode,'--core']);ob_end_clean();return $r;}
function sourceCoreRows(PDO $p):array{$r=[];foreach(array_keys(coreDefinitions()) as $t)$r[$t]=$p->query('SELECT * FROM `'.$t.'` ORDER BY id')->fetchAll();$r['ram_users']=$p->query('SELECT id FROM ram_users ORDER BY id')->fetchAll();return $r;}
$before=coreFingerprint(sourceCoreRows($reader));
coreCheck(invokeCore('--inspect')===0,'inspect');coreCheck(invokeCore('--dry-run')===0,'dry-run');
coreCheck((int)$a->query("SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA='fr7_core_a_test'")->fetchColumn()===0,'no DDL in dry run');
$all=[];$hashes=[];
foreach(['fr7_core_a_test','fr7_core_b_test'] as $db){
 putenv('FR7_TARGET_DB='.$db);coreCheck(invokeCore('--execute')===0,'execute');
 $data=[];foreach(coreDefinitions() as $def){$data[$def['target']]=$a->query('SELECT * FROM '.$db.'.'.$def['target'].' ORDER BY id')->fetchAll();}
 $all[]=$data;$hashes[]=$a->query('SELECT source_fingerprint FROM '.$db.'.migration_runs')->fetchColumn();
 coreCheck((int)$a->query('SELECT COUNT(*) FROM '.$db.'.migration_id_map')->fetchColumn()===7,'maps');
 coreCheck($a->query('SELECT status FROM '.$db.'.migration_runs')->fetchColumn()==='passed','audit passed');
 coreCheck(invokeCore('--execute')===1,'repeat populated rejected');
 coreCheck((int)$a->query('SELECT COUNT(*) FROM '.$db.'.migration_runs')->fetchColumn()===1,'no extra run');
}
coreCheck($all[0]===$all[1]&&$hashes[0]===$hashes[1],'reproducible all tables');
coreCheck($all[0]['vehicles'][0]['vehicle_type_id']==7,'sparse type ID preserved');
coreCheck($all[0]['operators'][0]['client_id']==8&&$all[0]['operators'][0]['vehicle_id']==9,'resolved relationships');
coreCheck($all[0]['operators'][1]['vehicle_id']===null&&$all[0]['operators'][1]['legacy_vehicle_id']==0,'zero normalized');
coreCheck($all[0]['operators'][1]['client_id']===null&&$all[0]['operators'][1]['legacy_client_id']==99,'orphan retained');
coreCheck($all[0]['clients'][1]['is_active']==0&&$all[0]['clients'][1]['deleted_at']===null,'invalid deletion preserved inactive');
coreCheck($all[0]['clients'][1]['legacy_administrative_percentage']==='15%'&&$all[0]['clients'][1]['administrative_percentage']===null,'ambiguous percentage retained');
coreCheck($before===coreFingerprint(sourceCoreRows($reader)),'source unchanged');
$denied=false;try{$reader->exec("UPDATE ram_clientes SET cliente='DENIED' WHERE id=8");}catch(PDOException $e){$denied=true;}coreCheck($denied,'source write denied');
// Force failure in the final table after earlier domains have been inserted.
$a->exec("ALTER TABLE fr7_source_test.ram_operadores MODIFY telefono VARCHAR(16)");
$a->exec("UPDATE fr7_source_test.ram_operadores SET telefono=REPEAT('x',16) WHERE id=21");

putenv('FR7_TARGET_DB=fr7_core_failure_test');coreCheck(invokeCore('--execute')===1,'late failure');
foreach(coreDefinitions() as $def)coreCheck((int)$a->query('SELECT COUNT(*) FROM fr7_core_failure_test.'.$def['target'])->fetchColumn()===0,'all domains rolled back');
coreCheck((int)$a->query('SELECT COUNT(*) FROM fr7_core_failure_test.migration_id_map')->fetchColumn()===0,'maps rolled back');
coreCheck((int)$a->query('SELECT COUNT(*) FROM fr7_core_failure_test.migration_exceptions')->fetchColumn()===0,'exceptions rolled back');
$run=$a->query('SELECT status,notes FROM fr7_core_failure_test.migration_runs')->fetch();coreCheck($run['status']==='failed'&&$run['notes']==='FAILED_STAGE:migration','failed audit sanitized');
echo "CORE INTEGRATION PASSED: {$count} checks".PHP_EOL;
