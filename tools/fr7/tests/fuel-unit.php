<?php
declare(strict_types=1);
require __DIR__.'/../migrate.php';
$n=0;
function checkFuel(bool $ok,string $m):void{global $n;if(!$ok)throw new RuntimeException($m);$n++;}
$r=['id'=>11,'asignacion_id'=>999,'fecha'=>'1899-11-30','gasolinera_id'=>0,'ticket'=>'original','litros'=>'0.00','precio'=>'2.00','total'=>'3.99','kilometraje'=>'7.00','rendimiento'=>'0.00','foto_ticket'=>'upl/ref.jpg','foto_tablero_antes'=>'','foto_tablero_despues'=>'','foto_tablero_km'=>'','created_at'=>null,'updated_at'=>null,'factura_proveedor'=>'DOC','dias_credito'=>0,'fecha_limite_pago'=>'0000-00-00'];
$p=['ram_asignaciones'=>[],'ram_unidades'=>[9=>true],'ram_gasolineras'=>[5=>true],'ram_users'=>[1=>true]];
[$a,$e]=fuelTransform('ram_asignaciones_combustibles',$r,$p);
checkFuel($a['id']==='22'&&$a['legacy_id']==='11'&&$a['legacy_source']==='assignment','ordinary identity');
checkFuel($a['assignment_id']===null&&$a['legacy_assignment_id']==='999','missing assignment retained');
checkFuel($a['fuel_station_id']===null&&$a['is_other_fuel_station']==='1','Other explicit');
checkFuel($a['business_date']===null&&$a['legacy_business_date']==='1899-11-30','anomalous business date retained');
checkFuel($a['total_amount']==='3.99'&&$a['liters']==='0.00','no recalculation or zero division');
checkFuel($a['ticket_photo_path']==='upl/ref.jpg','path reference retained');
checkFuel(count($e)===5,'all expected anomalies');
$r['unidad_id']=9;$r['user_id']=1;$r['fecha']='2026-11-01';$r['fecha_limite_pago']=null;$r['gasolinera_id']=5;$r['litros']='1.25';
[$b,$e]=fuelTransform('ram_asignaciones_especiales',$r,$p);
checkFuel($b['id']==='23'&&$b['id']!==$a['id'],'same legacy ID different context no collision');
checkFuel($b['assignment_id']===null&&$b['vehicle_id']==='9','special does not invent assignment');
checkFuel($b['legacy_user_id']==='1'&&$e===[],'user preserved valid');
checkFuel($b['total_amount']==='3.99','historic total preserved even when product differs');
echo "FUEL UNIT PASSED: {$n} checks".PHP_EOL;
