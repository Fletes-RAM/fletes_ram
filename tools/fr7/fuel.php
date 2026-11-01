<?php
declare(strict_types=1);
function fuelDefinitions():array {
    $common=['target'=>'fuel_loads','fields'=>['ticket'=>'ticket','foto_ticket'=>'ticket_photo_path','foto_tablero_antes'=>'dashboard_before_path','foto_tablero_despues'=>'dashboard_after_path','foto_tablero_km'=>'dashboard_odometer_path','factura_proveedor'=>'legacy_supplier_invoice','dias_credito'=>'credit_days'],
      'money'=>['litros'=>'liters','precio'=>'unit_price','total'=>'total_amount','kilometraje'=>'odometer','rendimiento'=>'kilometers_per_liter']];
    return operationDefinitions()+[
      'ram_asignaciones_combustibles'=>$common+['scope'=>'assignment','relations'=>['asignacion_id'=>['ram_asignaciones','assignment_id',false],'gasolinera_id'=>['ram_gasolineras','fuel_station_id',true]]],
      'ram_asignaciones_especiales'=>$common+['scope'=>'special','relations'=>['unidad_id'=>['ram_unidades','vehicle_id',false],'gasolinera_id'=>['ram_gasolineras','fuel_station_id',true]],'user_ref'=>true]
    ];
}
function fuelScope(array $definition):string {
    if(!isset($definition['scope']))return '';
    if(!in_array($definition['scope'],['assignment','special'],true))throw new RuntimeException('INVALID_FUEL_SCOPE');
    return " WHERE legacy_source='".$definition['scope']."'";
}
function fuelTransform(string $table,array $row,array $parents):array {
    if(isset(operationDefinitions()[$table]))return operationTransform($table,$row,$parents);
    $d=fuelDefinitions()[$table];
    // Reuse the reviewed operation mapping, with fuel's explicitly declared fields.
    if(!preg_match('/^[1-9][0-9]*$/D',(string)$row['id']) || strlen((string)$row['id'])>10)throw new RuntimeException('INVALID_FUEL_SOURCE_ID');
    $v=['id'=>(string)((int)$row['id']*2+($d['scope']==='special'?1:0)), 'legacy_id'=>(string)$row['id'],'legacy_source'=>$d['scope']];$e=[];
    foreach($d['fields'] as $src=>$dest)$v[$dest]=$row[$src];
    foreach($d['money'] as $src=>$dest){$v[$dest]=coreDecimal((string)$row[$src],2,15);if($v[$dest]===null)throw new RuntimeException('INVALID_FUEL_AMOUNT');}
    foreach(['created_at','updated_at'] as $f){$v[$f]=cleanDate($row[$f]);if($row[$f]!==null&&$v[$f]===null)$e[]=['code'=>'INVALID_DATE_TO_NULL','field'=>$f];}
    foreach(['fecha'=>'business_date','fecha_limite_pago'=>'payment_due_date'] as $src=>$dest){
        $raw=$row[$src];$date=coreDate($raw,true);
        if($date!==null && (int)substr($date,0,4)<=1900)$date=null;
        $v[$dest]=$date;$v['legacy_'.$dest]=$raw;
        if($raw!==null&&$date===null)$e[]=['code'=>'INVALID_DATE_TO_NULL','field'=>$src];
    }
    $v['assignment_id']=null;$v['legacy_assignment_id']=null;$v['vehicle_id']=null;$v['legacy_vehicle_id']=null;$v['legacy_user_id']=null;
    foreach($d['relations'] as $src=>[$parent,$dest,$zeroAllowed]){
        $raw=(string)$row[$src];$v['legacy_'.$dest]=$raw;
        if($raw==='0'&&$zeroAllowed){$v[$dest]=null;$e[]=['code'=>'OTHER_FUEL_STATION','field'=>$src];}
        elseif(isset($parents[$parent][$raw]))$v[$dest]=$raw;
        else{$v[$dest]=null;$e[]=['code'=>'ORPHAN_REFERENCE_PRESERVED','field'=>$src];}
    }
    $v['is_other_fuel_station']=(string)$row['gasolinera_id']==='0'?'1':'0';
    if($d['user_ref']??false){$v['legacy_user_id']=(string)$row['user_id'];if(!isset($parents['ram_users'][$v['legacy_user_id']]))$e[]=['code'=>'ORPHAN_USER_REFERENCE','field'=>'user_id'];}
    // Historic quantities are evidence: never recalculate total or divide by liters.
    if($v['liters']==='0.00')$e[]=['code'=>'ZERO_LITERS_PRESERVED','field'=>'litros'];
    return [$v,$e];
}
function fuelRelations():array {
    return array_merge(operationRelations(),[['fuel_loads','assignment_id','assignments'],['fuel_loads','vehicle_id','vehicles'],['fuel_loads','fuel_station_id','fuel_stations']]);
}
