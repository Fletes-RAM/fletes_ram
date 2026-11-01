<?php
declare(strict_types=1);
function operationDefinitions(): array {
    return coreDefinitions()+[
        'ram_nombres_rutas'=>['target'=>'routes','fields'=>['nombre'=>'name','observaciones'=>'notes'],'decimal_text'=>['total_km'=>'total_kilometers']],
        'ram_detalles_rutas'=>['target'=>'route_segments','fields'=>['estado'=>'origin_state','origen'=>'origin','estado_destino'=>'destination_state','destino'=>'destination'],'decimal_text'=>['km'=>'kilometers'],'relations'=>['nombre_id'=>['ram_nombres_rutas','route_id',false]]],
        'ram_rendimientos'=>['target'=>'fuel_efficiency_parameters','fields'=>[],'decimal_text'=>['rendimiento'=>'kilometers_per_liter'],'relations'=>['tipo_de_unidad_id'=>['ram_tipos_de_unidades','vehicle_type_id',false]]],
        'ram_cotizaciones'=>['target'=>'quotations','fields'=>['folio'=>'reference','observaciones'=>'notes'],'decimal_text'=>['tot_km'=>'total_kilometers'],'money'=>['costo_combustible'=>'fuel_unit_cost','propuesta'=>'proposed_amount','utilidad'=>'profit','sueldo_ope'=>'operator_pay','gastos_admon'=>'administrative_expenses','otros_gastos'=>'other_expenses','combustible'=>'fuel_amount','caseta'=>'toll_amount'],'relations'=>['cliente_id'=>['ram_clientes','client_id',false],'ruta_id'=>['ram_nombres_rutas','route_id',false],'tipo_de_unidad_id'=>['ram_tipos_de_unidades','vehicle_type_id',false],'rendimiento_id'=>['ram_rendimientos','fuel_efficiency_parameter_id',false]]],
        'ram_asignaciones'=>['target'=>'assignments','fields'=>['terminado'=>'legacy_status'],'relations'=>['cotizacion_id'=>['ram_cotizaciones','quotation_id',false],'unidad_id'=>['ram_unidades','vehicle_id',false]],'user_ref'=>true],
        'ram_gasolineras'=>['target'=>'fuel_stations','fields'=>['gasolinera'=>'name','estacion'=>'station','contacto'=>'contact_name','email'=>'email','telefono'=>'phone','observaciones'=>'notes']],
    ];
}
function operationTransform(string $table,array $row,array $parents): array {
    if(isset(coreDefinitions()[$table]))return coreTransform($table,$row,$parents);
    if(!preg_match('/^[1-9][0-9]*$/D',(string)$row['id']))throw new RuntimeException('INVALID_SOURCE_ID');
    $d=operationDefinitions()[$table];$v=['id'=>(string)$row['id'],'legacy_id'=>(string)$row['id']];$exceptions=[];
    foreach($d['fields'] as $src=>$dest)$v[$dest]=$row[$src];
    foreach(['created_at','updated_at','deleted_at'] as $f)if(array_key_exists($f,$row)){
        $v[$f]=cleanDate($row[$f]);if($row[$f]!==null&&$v[$f]===null)$exceptions[]=['code'=>'INVALID_DATE_TO_NULL','field'=>$f];
    }
    if(array_key_exists('deleted_at',$row))$v['is_active']=$row['deleted_at']===null?'1':'0';
    foreach($d['decimal_text']??[] as $src=>$dest){
        $v[$dest]=coreDecimal((string)$row[$src],4,18);$v['legacy_'.$dest]=(string)$row[$src];
        if($v[$dest]===null)$exceptions[]=['code'=>'DECIMAL_TEXT_PRESERVED','field'=>$src];
    }
    foreach($d['money']??[] as $src=>$dest){
        $v[$dest]=coreDecimal((string)$row[$src],2,15);if($v[$dest]===null)throw new RuntimeException('INVALID_SOURCE_AMOUNT');
    }
    foreach($d['relations']??[] as $src=>[$parent,$dest,$zeroAllowed]){
        $raw=(string)$row[$src];$v['legacy_'.$dest]=$raw;
        if(isset($parents[$parent][$raw]))$v[$dest]=$raw;
        else{$v[$dest]=null;$exceptions[]=['code'=>$raw==='0'&&$zeroAllowed?'ZERO_REFERENCE_TO_NULL':'ORPHAN_REFERENCE_PRESERVED','field'=>$src];}
    }
    if($d['user_ref']??false){$v['legacy_user_id']=(string)$row['user_id'];if(!isset($parents['ram_users'][$v['legacy_user_id']]))$exceptions[]=['code'=>'ORPHAN_USER_REFERENCE','field'=>'user_id'];}
    return [$v,$exceptions];
}
function operationRelations(): array {
    $defs=operationDefinitions();$out=[['vehicles','vehicle_type_id','vehicle_types'],['operators','vehicle_id','vehicles'],['operators','client_id','clients']];
    foreach($defs as $d)foreach($d['relations']??[] as [$parent,$column])$out[]=[$d['target'],$column,$defs[$parent]['target']];
    return $out;
}
