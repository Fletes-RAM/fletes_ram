<?php
declare(strict_types=1);
require __DIR__.'/../migrate.php';
$count=0;
function expectCore(bool $ok,string $message): void {global $count;if(!$ok)throw new RuntimeException($message);$count++;}
foreach(['0'=>'0.0000','15'=>'15.0000','001.25'=>'1.2500','-0.00'=>'0.0000','+12.345600'=>'12.3456'] as $in=>$out)expectCore(coreDecimal((string)$in,4,14)===$out,'exact decimal');
foreach(['',' 15','15%','1,5','1e2','NaN','1.23456','10000000000'] as $in)expectCore(coreDecimal($in,4,14)===null,'ambiguous decimal rejected');
expectCore(coreDate('2024-02-29',true)==='2024-02-29','valid date');
expectCore(coreDate('2025-02-29',true)===null,'invalid date');
$r=['id'=>8,'cliente'=>'  Nombre sintético  ','nombre_contacto'=>'Contacto','email'=>'fixture@example.invalid','telefono'=>'000','observaciones'=>'nota','gasto_admon'=>'15%','created_at'=>'0000-00-00 00:00:00','updated_at'=>'2026-01-01 00:00:00','deleted_at'=>'0000-00-00 00:00:00'];
[$v,$e]=coreTransform('ram_clientes',$r,[]);
expectCore($v['id']==='8'&&$v['legacy_id']==='8','IDs retained');
expectCore($v['name']===$r['cliente'],'name whitespace retained');
expectCore($v['administrative_percentage']===null&&$v['legacy_administrative_percentage']==='15%','original nonnumeric retained');
expectCore($v['is_active']==='0'&&$v['deleted_at']===null,'invalid deletion never reactivates');
expectCore(count($e)===3,'date and decimal exceptions');
$r=['id'=>4,'user_id'=>99,'nss'=>'','telefono'=>'','contacto'=>'','tel_contacto'=>'','licencia'=>'','vigencia'=>'0000-00-00','medica'=>'2024-02-29','unidad_id'=>7,'cliente_id'=>0,'origen'=>'','destino'=>'','estatus'=>'original','observaciones'=>'','created_at'=>'2026-01-01 00:00:00','updated_at'=>'2026-01-01 00:00:00','deleted_at'=>null];
[$v,$e]=coreTransform('ram_operadores',$r,['ram_unidades'=>[],'ram_clientes'=>[],'ram_users'=>[]]);
expectCore($v['vehicle_id']===null&&$v['legacy_vehicle_id']==='7','orphan ref preserved');
expectCore($v['client_id']===null&&$v['legacy_client_id']==='0','zero ref normalized');
expectCore($v['legacy_user_id']==='99','user reference preserved');
expectCore(array_column($e,'code')===['INVALID_DATE_TO_NULL','ORPHAN_REFERENCE_PRESERVED','ZERO_REFERENCE_TO_NULL','ORPHAN_USER_REFERENCE'],'all anomalies explicit');
$one=['ram_users'=>[['id'=>1]],'ram_clientes'=>[['id'=>8,'cliente'=>'x']]];
$two=$one;$two['ram_users'][0]['id']=2;
expectCore(coreFingerprint($one)!==coreFingerprint($two),'fingerprint covers reference IDs');
$two=$one;$two['ram_clientes'][0]['cliente']='y';
expectCore(coreFingerprint($one)!==coreFingerprint($two),'fingerprint covers contents');
echo "CORE UNIT PASSED: {$count} checks".PHP_EOL;
