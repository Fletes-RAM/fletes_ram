<?php
declare(strict_types=1);
// Explicit, reviewed mappings; never derive relationships from column suffixes.
function coreDefinitions(): array {
    return [
        'ram_clientes'=>['target'=>'clients','fields'=>['cliente'=>'name','nombre_contacto'=>'contact_name','email'=>'email','telefono'=>'phone','observaciones'=>'notes']],
        'ram_tipos_de_unidades'=>['target'=>'vehicle_types'],
        'ram_unidades'=>['target'=>'vehicles','fields'=>['unidad'=>'name','placas'=>'license_plate','serie'=>'serial_number','poliza'=>'insurance_policy','aseguradora'=>'insurer','observaciones'=>'notes']],
        'ram_operadores'=>['target'=>'operators','fields'=>['nss'=>'social_security_number','telefono'=>'phone','contacto'=>'emergency_contact','tel_contacto'=>'emergency_phone','licencia'=>'license','origen'=>'current_origin','destino'=>'current_destination','estatus'=>'legacy_status','observaciones'=>'notes']],
    ];
}
function coreDate($value, bool $dateOnly=false): ?string {
    if (!$dateOnly) return cleanDate($value);
    return $value !== null && cleanDate($value.' 00:00:00') !== null ? $value : null;
}
function coreDecimal(string $value, int $scale, int $precision): ?string {
    // No floats, locale guesses, rounding, scientific notation or silent truncation.
    if (!preg_match('/^([+-]?)([0-9]+)(?:\.([0-9]+))?$/D', $value, $m)) return null;
    $whole=ltrim($m[2],'0'); if ($whole==='') $whole='0';
    $fraction=$m[3]??'';
    if (strlen($whole)>$precision-$scale || (strlen($fraction)>$scale && trim(substr($fraction,$scale),'0')!=='')) return null;
    $sign=$m[1]==='-' && ($whole!=='0'||trim($fraction,'0')!=='') ? '-' : '';
    return $sign.$whole.'.'.str_pad(substr($fraction,0,$scale),$scale,'0');
}
function coreTransform(string $table, array $row, array $parents): array {
    if (!preg_match('/^[1-9][0-9]*$/D',(string)$row['id'])) throw new RuntimeException('INVALID_SOURCE_ID');
    $exceptions=[];
    if ($table==='ram_tipos_de_unidades') {
        [$v,$dates]=transformVehicleType($row);
        foreach($dates as $f) $exceptions[]=['code'=>'INVALID_DATE_TO_NULL','field'=>$f];
        return [['id'=>(string)$row['id']]+$v,$exceptions];
    }
    $v=['id'=>(string)$row['id'],'legacy_id'=>(string)$row['id']];
    foreach(coreDefinitions()[$table]['fields'] as $src=>$dst) $v[$dst]=$row[$src];
    $dateFields=['created_at'=>false,'updated_at'=>false,'deleted_at'=>false];
    if($table==='ram_unidades') $dateFields['vigencia']=true;
    if($table==='ram_operadores') {$dateFields['vigencia']=true;$dateFields['medica']=true;}
    foreach($dateFields as $f=>$dateOnly) {
        $dest=['vigencia'=>'valid_until','medica'=>'medical_valid_until'][$f]??$f;
        $v[$dest]=coreDate($row[$f],$dateOnly);
        if($row[$f]!==null && $v[$dest]===null) $exceptions[]=['code'=>'INVALID_DATE_TO_NULL','field'=>$f];
    }
    $v['is_active']=$row['deleted_at']===null?'1':'0';
    if($table==='ram_clientes') {
        $v['administrative_percentage']=coreDecimal($row['gasto_admon'],4,14);
        $v['legacy_administrative_percentage']=$row['gasto_admon'];
        if($v['administrative_percentage']===null) $exceptions[]=['code'=>'DECIMAL_TEXT_PRESERVED','field'=>'gasto_admon'];
    }
    if($table==='ram_unidades') {
        $v['initial_odometer']=coreDecimal((string)$row['km_inicial'],2,15);
        if($v['initial_odometer']===null) throw new RuntimeException('INVALID_SOURCE_ODOMETER');
    }
    $relations=match($table){
        'ram_unidades'=>['tipo_de_unidad_id'=>['ram_tipos_de_unidades','vehicle_type_id',false]],
        'ram_operadores'=>['unidad_id'=>['ram_unidades','vehicle_id',true],'cliente_id'=>['ram_clientes','client_id',true]],
        default=>[]
    };
    foreach($relations as $field=>[$parent,$dest,$zeroAllowed]) {
        $raw=(string)$row[$field];$v['legacy_'.$dest]=$raw;
        if($raw==='0' && $zeroAllowed) {$v[$dest]=null;$exceptions[]=['code'=>'ZERO_REFERENCE_TO_NULL','field'=>$field];}
        elseif(isset($parents[$parent][$raw])) $v[$dest]=$raw;
        else {$v[$dest]=null;$exceptions[]=['code'=>'ORPHAN_REFERENCE_PRESERVED','field'=>$field];}
    }
    if($table==='ram_operadores') {
        $v['legacy_user_id']=(string)$row['user_id'];
        if(!isset($parents['ram_users'][$v['legacy_user_id']])) $exceptions[]=['code'=>'ORPHAN_USER_REFERENCE','field'=>'user_id'];
    }
    return [$v,$exceptions];
}
function coreFingerprint(array $domains): string {
    $ctx=hash_init('sha256');hash_update($ctx,'fr7:core:v1:');
    foreach($domains as $table=>$rows) {
        hash_update($ctx,strlen($table).':'.$table);
        foreach($rows as $row){$json=json_encode($row,JSON_THROW_ON_ERROR|JSON_UNESCAPED_UNICODE);hash_update($ctx,strlen($json).':'.$json);}
    }
    return hash_final($ctx);
}
function declaredSourceColumns(string $table,array $definition):array {
    if($table==='ram_tipos_de_unidades')return ['id','tipo_de_unidad','porcentaje','observaciones','deleted_at','created_at','updated_at'];
    $columns=array_merge(['id','created_at','updated_at'],array_keys($definition['fields']),array_keys($definition['decimal_text']??[]),array_keys($definition['money']??[]),array_keys($definition['relations']??[]));
    $extra=match($table){
        'ram_clientes'=>['gasto_admon','deleted_at'],
        'ram_unidades'=>['tipo_de_unidad_id','vigencia','km_inicial','deleted_at'],
        'ram_operadores'=>['user_id','vigencia','medica','unidad_id','cliente_id','deleted_at'],
        'ram_nombres_rutas','ram_detalles_rutas','ram_cotizaciones','ram_gasolineras'=>['deleted_at'],
        'ram_asignaciones'=>['user_id'],
        'ram_asignaciones_combustibles'=>['fecha','fecha_limite_pago'],
        'ram_asignaciones_especiales'=>['fecha','fecha_limite_pago','user_id'],
        default=>[]
    };
    return array_values(array_unique(array_merge($columns,$extra,array_keys($definition['dates']??[]),$definition['extra_columns']??[])));
}
