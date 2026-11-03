<?php
declare(strict_types=1);
require __DIR__.'/../migrate.php';
$n=0;function checkPay(bool $ok,string $m):void{global $n;if(!$ok)throw new RuntimeException($m);$n++;}
checkPay(supplierInstallmentBalance('1000',[])===['paid'=>'0.00','balance'=>'1000.00','state'=>'pending'],'initial balance');
checkPay(supplierInstallmentBalance('1000',['300','400'])===['paid'=>'700.00','balance'=>'300.00','state'=>'partial'],'partial installments');
checkPay(supplierInstallmentBalance('1000',['300','400'],'300')===['paid'=>'1000.00','balance'=>'0.00','state'=>'paid'],'final installment');
checkPay(supplierInstallmentBalance('0.30',['0.10'],'0.20')['balance']==='0.00','decimal exact');
checkPay(supplierInstallmentBalance('9999999999999.99',[],'0.01')['balance']==='9999999999999.98','large exact balance');
foreach(['0','-1','0.001','1,000','301'] as $v){$bad=false;try{supplierInstallmentBalance('1000',['300','400'],$v);}catch(RuntimeException $e){$bad=true;}checkPay($bad,'invalid or excessive installment rejected');}
$r=['id'=>1,'comprobante_id'=>'a-11','factura'=>'F','fecha'=>'2026-01-01','valor_factura'=>'100','banco_id'=>'1','categoria_id'=>'1','subcategoria_id'=>'1','observaciones'=>'','created_at'=>'2026-01-01 00:00:00','updated_at'=>'2026-01-01 00:00:00'];
$p=['ram_asignaciones_combustibles'=>[11=>true],'ram_asignaciones_especiales'=>[11=>true],'ram_bancos'=>[1=>true],'ram_bancos_categorias'=>[1=>true],'ram_bancos_subcategorias'=>[1=>true]];$c=['fuel_stations'=>['ram_asignaciones_combustibles'=>[11=>'5'],'ram_asignaciones_especiales'=>[11=>'0']]];
[$a,$e]=payablesTransform('ram_proveedores',$r,$p,$c);checkPay($a['fuel_load_id']==='22'&&$a['reported_amount']==='100.00'&&$e===[],'known fuel reference');
$r['id']=2;[$b]=payablesTransform('ram_proveedores',$r,$p,$c);checkPay($a['candidate_key']===$b['candidate_key'],'candidate signature retained without erasing rows');
$r['comprobante_id']='e-11';[$b]=payablesTransform('ram_proveedores',$r,$p,$c);$r['id']=3;[$d]=payablesTransform('ram_proveedores',$r,$p,$c);checkPay($b['candidate_key']!==$d['candidate_key'],'Other station never implies common supplier');
$r['comprobante_id']='a-999';[$b,$e]=payablesTransform('ram_proveedores',$r,$p,$c);checkPay($b['fuel_load_id']===null&&$b['fuel_legacy_id']==='999'&&$b['legacy_receipt_reference']==='a-999','orphan receipt preserved');
$r['comprobante_id']='a-011';[$b,$e]=payablesTransform('ram_proveedores',$r,$p,$c);checkPay($b['fuel_load_id']===null&&$b['legacy_receipt_reference']==='a-011','noncanonical reference not coerced');
$r['valor_factura']='1,000.00';[$b,$e]=payablesTransform('ram_proveedores',$r,$p,$c);checkPay($b['reported_amount']===null&&$b['legacy_reported_amount']==='1,000.00','ambiguous amount preserved');
echo "PAYABLES UNIT PASSED: {$n} checks".PHP_EOL;
