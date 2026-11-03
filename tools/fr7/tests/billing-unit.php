<?php
declare(strict_types=1);
require __DIR__.'/../migrate.php';
$n=0;
function checkBilling(bool $ok,string $label):void{global $n;if(!$ok)throw new RuntimeException($label);$n++;}
$parents=['ram_bancos'=>[1=>true],'ram_bancos_categorias'=>[1=>true],'ram_bancos_subcategorias'=>[1=>true],'ram_clientes'=>[8=>true],'ram_cotizaciones'=>[22=>true],'ram_controles_vehiculares'=>[100=>true],'ram_users'=>[1=>true],'ram_facturas_controles'=>[1=>true]];
$row=['id'=>1,'bancos_id'=>1,'periodo'=>'2026-01','categoria_id'=>1,'subcategoria_id'=>'1','movimiento'=>'Synthetic','folio'=>'F','fecha'=>'2026-01-01','tipo'=>'-1','cantidad'=>'-12.34','observaciones'=>'','created_at'=>'2026-01-01 00:00:00','updated_at'=>'2026-01-01 00:00:00'];
[$v,$e]=billingTransform('ram_bancos_movimientos',$row,$parents,[]);checkBilling($v['direction']==='inflow'&&$v['amount']==='12.34'&&$v['legacy_quantity']==='-12.34','signed economic effect');
$row['tipo']='1';[$v]=billingTransform('ram_bancos_movimientos',$row,$parents,[]);checkBilling($v['direction']==='outflow','negative quantity positive sign');
$row['cantidad']='0';[$v]=billingTransform('ram_bancos_movimientos',$row,$parents,[]);checkBilling($v['direction']==='none','zero effect');
$row['tipo']=' 1';[$v,$e]=billingTransform('ram_bancos_movimientos',$row,$parents,[]);checkBilling($v['direction']===null&&$v['legacy_type']===' 1'&&in_array('UNKNOWN_FINANCIAL_SIGN',array_column($e,'code'),true),'no whitespace sign inference');
$row['subcategoria_id']='01';[$v]=billingTransform('ram_bancos_movimientos',$row,$parents,[]);checkBilling($v['subcategory_id']===null&&$v['legacy_subcategory_id']==='01','no textual ID coercion');
$i=['id'=>1,'cotizacion_id'=>22,'factura'=>'F','subtotal'=>'100.25','maniobras'=>'0','otros'=>'0','iva'=>'16','retencion'=>'0','total'=>'999.99','observaciones'=>'','pagada'=>1,'fecha_pago'=>'0000-00-00','created_at'=>'2026-01-01 00:00:00','updated_at'=>'2026-01-01 00:00:00'];
[$a,$e]=billingTransform('ram_facturas',$i,$parents,['quotation_clients'=>[22=>'8']]);
checkBilling($a['id']==='2'&&$a['legacy_source']==='quotation','quotation namespace');
checkBilling($a['client_id']==='8'&&$a['quotation_id']==='22','confirmed quotation client');
checkBilling($a['is_paid']==='1'&&$a['payment_date']===null&&$a['legacy_payment_date']==='0000-00-00','paid date not invented');
checkBilling($a['total_amount']==='999.99','stored invoice total never recalculated');
checkBilling(in_array('PAID_WITHOUT_VALID_PAYMENT_DATE',array_column($e,'code'),true),'missing payment evidence audited');
$i['control_id']=0;$i['cliente_id']=8;unset($i['cotizacion_id']);[$b]=billingTransform('ram_facturas_controles',$i,$parents,[]);
checkBilling($b['id']==='3'&&$b['legacy_id']==='1'&&$b['is_grouped']==='1','overlapping control invoice ID retained');
checkBilling($b['quotation_id']===null&&$b['legacy_control_id']==='0','no invented quotation or control');
$i['pagada']=2;[$b,$e]=billingTransform('ram_facturas_controles',$i,$parents,[]);checkBilling($b['is_paid']===null&&$b['legacy_paid_flag']==='2','unknown paid flag retained');
$spool=tmpfile();spoolWrite($spool,['values'=>['id'=>1,'notes'=>"línea\nsegunda"],'exceptions'=>[]]);spoolWrite($spool,['values'=>['id'=>2,'notes'=>''],'exceptions'=>[]]);
$values=iterator_to_array(spoolValues($spool),false);checkBilling(count($values)===2&&$values[0]['notes']==="línea\nsegunda",'spool round trip with embedded newline');
checkBilling(iterator_to_array(spoolValues($spool),false)===$values,'spool repeatable reads');fclose($spool);
echo "BILLING UNIT PASSED: {$n} checks".PHP_EOL;
