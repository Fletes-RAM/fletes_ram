<?php
declare(strict_types=1);
function billingDefinitions():array {
    $invoice=['target'=>'invoices','domain'=>'billing','fields'=>['factura'=>'reference','observaciones'=>'notes'],
        'money'=>['subtotal'=>'subtotal','maniobras'=>'handling_amount','otros'=>'other_amount','iva'=>'tax_amount','retencion'=>'withholding_amount','total'=>'total_amount']];
    return operationDefinitions()+[
        'ram_bancos'=>['target'=>'financial_accounts','domain'=>'finance','fields'=>['banco'=>'name','no_cuenta'=>'account_number','clabe'=>'banking_code','observaciones'=>'notes'],'extra_columns'=>['deleted_at']],
        'ram_bancos_categorias'=>['target'=>'financial_categories','domain'=>'finance','fields'=>['categoria'=>'name']],
        'ram_bancos_subcategorias'=>['target'=>'financial_subcategories','domain'=>'finance','fields'=>['subcategoria'=>'name'],'relations'=>['categoria_id'=>['ram_bancos_categorias','category_id',false]]],
        'ram_bancos_movimientos'=>['target'=>'financial_movements','domain'=>'finance','fields'=>['periodo'=>'legacy_period','movimiento'=>'description','folio'=>'reference','observaciones'=>'notes'],
            'money'=>['cantidad'=>'legacy_quantity'], 'dates'=>['fecha'=>['business_date',true]],
            'relations'=>['bancos_id'=>['ram_bancos','account_id',false],'categoria_id'=>['ram_bancos_categorias','category_id',false],'subcategoria_id'=>['ram_bancos_subcategorias','subcategory_id',false]],'extra_columns'=>['tipo']],
        'ram_controles_vehiculares'=>['target'=>'vehicle_controls','domain'=>'billing','fields'=>['porcentaje'=>'legacy_percentage','control_vehicular'=>'reference','origen'=>'legacy_origin','pagado'=>'legacy_paid_status'],
            'decimal_text'=>['toneladas'=>'tonnage','tarifa'=>'rate','cantidad'=>'amount','iva'=>'tax_amount'],
            'dates'=>['fecha'=>['business_date',true]],'extra_columns'=>['user_id','factura_id']],
        'ram_facturas'=>$invoice+['scope'=>'quotation','extra_columns'=>['cotizacion_id','pagada','fecha_pago']],
        'ram_facturas_controles'=>$invoice+['scope'=>'control','extra_columns'=>['control_id','cliente_id','pagada','fecha_pago']],
    ];
}
function mappedBillingTransform(array $d,array $row,array $parents):array {
    $v=['id'=>(string)$row['id'],'legacy_id'=>(string)$row['id']];$e=[];
    foreach($d['fields'] as $src=>$dst)$v[$dst]=$row[$src];
    foreach(['created_at','updated_at','deleted_at'] as $f)if(array_key_exists($f,$row)){
        $v[$f]=cleanDate($row[$f]);if($row[$f]!==null&&$v[$f]===null)$e[]=['code'=>'INVALID_DATE_TO_NULL','field'=>$f];
    }
    if(array_key_exists('deleted_at',$row))$v['is_active']=$row['deleted_at']===null?'1':'0';
    foreach($d['dates']??[] as $src=>[$dst,$dateOnly]){
        $v[$dst]=coreDate($row[$src],$dateOnly);$v['legacy_'.$dst]=$row[$src];
        if($row[$src]!==null&&$v[$dst]===null)$e[]=['code'=>'INVALID_DATE_TO_NULL','field'=>$src];
    }
    foreach($d['money']??[] as $src=>$dst){$v[$dst]=coreDecimal((string)$row[$src],2,15);if($v[$dst]===null)throw new RuntimeException('INVALID_SOURCE_AMOUNT');}
    foreach($d['decimal_text']??[] as $src=>$dst){
        $v[$dst]=coreDecimal((string)$row[$src],4,18);$v['legacy_'.$dst]=(string)$row[$src];
        if($v[$dst]===null)$e[]=['code'=>'DECIMAL_TEXT_PRESERVED','field'=>$src];
    }
    foreach($d['relations']??[] as $src=>[$parent,$dst,$zero]){
        $v['legacy_'.$dst]=(string)$row[$src];
        if(isset($parents[$parent][(string)$row[$src]]))$v[$dst]=(string)$row[$src];
        else{$v[$dst]=null;$e[]=['code'=>$zero&&(string)$row[$src]==='0'?'ZERO_REFERENCE_TO_NULL':'ORPHAN_REFERENCE_PRESERVED','field'=>$src];}
    }
    return [$v,$e];
}
function billingTransform(string $table,array $row,array $parents,array $context):array {
    if(isset(operationDefinitions()[$table]))return operationTransform($table,$row,$parents);
    if(!preg_match('/^[1-9][0-9]*$/D',(string)$row['id']))throw new RuntimeException('INVALID_SOURCE_ID');
    $d=billingDefinitions()[$table];[$v,$e]=mappedBillingTransform($d,$row,$parents);
    if($table==='ram_bancos_movimientos'){
        $v['legacy_type']=(string)$row['tipo'];$v['amount']=ltrim($v['legacy_quantity'],'-');$v['direction']=null;
        if(in_array($v['legacy_type'],['1','-1'],true)){
            $negative=str_starts_with($v['legacy_quantity'],'-');
            $sign=($negative?-1:1)*(int)$v['legacy_type'];
            $v['direction']=$v['amount']==='0.00'?'none':($sign===1?'inflow':'outflow');
        }else $e[]=['code'=>'UNKNOWN_FINANCIAL_SIGN','field'=>'tipo'];
    }
    if($table==='ram_controles_vehiculares'){
        $v['legacy_user_id']=(string)$row['user_id'];
        if(!isset($parents['ram_users'][$v['legacy_user_id']]))$e[]=['code'=>'ORPHAN_USER_REFERENCE','field'=>'user_id'];
        $v['legacy_invoice_id']=$row['factura_id']===null?null:(string)$row['factura_id'];$v['invoice_id']=null;
        if($v['legacy_invoice_id']!==null){
            if(isset($parents['ram_facturas_controles'][$v['legacy_invoice_id']]))$v['invoice_id']=(string)((int)$v['legacy_invoice_id']*2+1);
            else $e[]=['code'=>'ORPHAN_REFERENCE_PRESERVED','field'=>'factura_id'];
        }
    }
    if($table==='ram_facturas'||$table==='ram_facturas_controles'){
        if(strlen((string)$row['id'])>10)throw new RuntimeException('INVALID_INVOICE_ID');
        $control=$d['scope']==='control';
        $v['id']=(string)((int)$row['id']*2+($control?1:0));$v['legacy_source']=$d['scope'];
        $v['legacy_paid_flag']=(string)$row['pagada'];$v['is_paid']=in_array($v['legacy_paid_flag'],['0','1'],true)?$v['legacy_paid_flag']:null;
        if($v['is_paid']===null)$e[]=['code'=>'UNKNOWN_PAID_FLAG','field'=>'pagada'];
        $v['legacy_payment_date']=$row['fecha_pago'];$v['payment_date']=coreDate($row['fecha_pago'],true);
        if($row['fecha_pago']!==null&&$v['payment_date']===null)$e[]=['code'=>'INVALID_DATE_TO_NULL','field'=>'fecha_pago'];
        if($v['is_paid']==='1'&&$v['payment_date']===null)$e[]=['code'=>'PAID_WITHOUT_VALID_PAYMENT_DATE','field'=>'fecha_pago'];
        if($v['is_paid']==='0'&&$v['payment_date']!==null)$e[]=['code'=>'UNPAID_WITH_PAYMENT_DATE','field'=>'fecha_pago'];
        $v['legacy_quotation_id']=$control?null:(string)$row['cotizacion_id'];$v['quotation_id']=null;
        $v['legacy_control_id']=$control?(string)$row['control_id']:null;
        $v['is_grouped']=$control&&(string)$row['control_id']==='0'?'1':'0';
        if(!$control){
            if(isset($parents['ram_cotizaciones'][$v['legacy_quotation_id']]))$v['quotation_id']=$v['legacy_quotation_id'];
            else $e[]=['code'=>'ORPHAN_REFERENCE_PRESERVED','field'=>'cotizacion_id'];
        }
        $client=$control?(string)$row['cliente_id']:($context['quotation_clients'][$v['legacy_quotation_id']]??null);
        $v['legacy_client_id']=$client;$v['client_id']=null;
        if($client!==null&&isset($parents['ram_clientes'][$client]))$v['client_id']=$client;
        elseif($client!==null)$e[]=['code'=>'ORPHAN_REFERENCE_PRESERVED','field'=>'cliente_id'];
        if($control&&$v['legacy_control_id']!=='0'&&!isset($parents['ram_controles_vehiculares'][$v['legacy_control_id']]))$e[]=['code'=>'ORPHAN_REFERENCE_PRESERVED','field'=>'control_id'];
    }
    return [$v,$e];
}
function billingScope(array $definition):string {
    if(!isset($definition['scope']))return '';
    if(!in_array($definition['scope'],['quotation','control'],true))throw new RuntimeException('INVALID_INVOICE_SCOPE');
    return " WHERE legacy_source='".$definition['scope']."'";
}
function validateDerivedBillingMaps(PDO $p,string $runId):bool {
    $collation=mappingIdCollation($p);
    $queries=[
        "SELECT COUNT(*) FROM invoice_sources s JOIN invoices i ON i.id=s.invoice_id LEFT JOIN migration_id_map m
        ON m.migration_run_id=? AND m.domain='billing' AND m.source_table=CASE i.legacy_source WHEN 'quotation' THEN 'ram_facturas' ELSE 'ram_facturas_controles' END
        AND m.source_id=CONCAT(i.legacy_id,':',s.source_type,':',s.legacy_source_id) COLLATE ".$collation."
        AND m.target_table='invoice_sources' AND CAST(m.target_id AS BINARY)=CAST(s.id AS BINARY) AND m.action='mapped' WHERE m.id IS NULL",
        "SELECT COUNT(*) FROM collections c JOIN invoices i ON i.id=c.invoice_id LEFT JOIN migration_id_map m
        ON m.migration_run_id=? AND m.domain='billing' AND m.source_table=CASE i.legacy_source WHEN 'quotation' THEN 'ram_facturas' ELSE 'ram_facturas_controles' END
        AND m.source_id=CAST(i.legacy_id AS CHAR CHARACTER SET utf8mb4) COLLATE ".$collation."
        AND m.target_table='collections' AND CAST(m.target_id AS BINARY)=CAST(c.id AS BINARY) AND m.action='mapped' WHERE m.id IS NULL"
    ];
    foreach($queries as $sql){$q=$p->prepare($sql);$q->execute([$runId]);if((int)$q->fetchColumn()!==0)return false;}
    return true;
}
function deriveBilling(PDO $p,string $runId):array {
    $p->exec("INSERT INTO invoice_sources (invoice_id,source_type,legacy_source_id,quotation_id,control_id,evidence_flags)
        SELECT id,'quotation',legacy_quotation_id,quotation_id,NULL,1 FROM invoices WHERE legacy_source='quotation' ORDER BY id");
    $p->exec("INSERT INTO invoice_sources (invoice_id,source_type,legacy_source_id,quotation_id,control_id,evidence_flags)
        SELECT i.id,'control',i.legacy_control_id,NULL,c.id,1 FROM invoices i LEFT JOIN vehicle_controls c ON c.legacy_id=i.legacy_control_id
        WHERE i.legacy_source='control' AND i.legacy_control_id<>0 ORDER BY i.id");
    // The two directions describe the same edge when both agree; merge only this reviewed key.
    $p->exec("INSERT INTO invoice_sources (invoice_id,source_type,legacy_source_id,quotation_id,control_id,evidence_flags)
        SELECT c.invoice_id,'control',c.legacy_id,NULL,c.id,2 FROM vehicle_controls c WHERE c.invoice_id IS NOT NULL ORDER BY c.id
        ON DUPLICATE KEY UPDATE evidence_flags=evidence_flags | 2");
    $p->exec("INSERT INTO collections (id,invoice_id,record_kind,claimed_amount,paid_on,financial_movement_id,reconciliation_status)
        SELECT id,id,'legacy_paid_summary',total_amount,payment_date,NULL,
        CASE WHEN payment_date IS NULL THEN 'invalid_payment_date' WHEN reference='' THEN 'empty_reference' ELSE 'missing' END FROM invoices WHERE is_paid=1 ORDER BY id");
    // Folio alone is insufficient. Exact context, date and amount are all required.
    $p->exec("INSERT INTO collection_movement_candidates (collection_id,financial_movement_id)
        SELECT c.id,m.id FROM collections c JOIN invoices i ON i.id=c.invoice_id JOIN financial_movements m
          ON m.reference=i.reference AND CAST(m.reference AS BINARY)=CAST(i.reference AS BINARY)
          AND m.business_date=i.payment_date AND m.amount=i.total_amount
          AND CAST(m.description AS BINARY)=CAST(CONCAT(CASE i.legacy_source WHEN 'control' THEN 'Pago Factura CV' ELSE 'Pago Factura ' END,i.reference) AS BINARY)
        WHERE i.payment_date IS NOT NULL AND i.reference<>'' AND m.direction='inflow'
          AND CAST(m.legacy_type AS BINARY)=CAST('1' AS BINARY) AND m.legacy_quantity>=0 ORDER BY c.id,m.id");
    $p->exec("UPDATE collections c JOIN (SELECT collection_id,MIN(financial_movement_id) AS movement_id FROM collection_movement_candidates GROUP BY collection_id HAVING COUNT(*)=1) a ON a.collection_id=c.id
        JOIN (SELECT financial_movement_id FROM collection_movement_candidates GROUP BY financial_movement_id HAVING COUNT(*)=1) b ON b.financial_movement_id=a.movement_id
        SET c.financial_movement_id=a.movement_id,c.reconciliation_status='linked'");
    $p->exec("UPDATE collections c SET reconciliation_status='ambiguous' WHERE financial_movement_id IS NULL AND EXISTS (SELECT 1 FROM collection_movement_candidates x WHERE x.collection_id=c.id)");
    $q=$p->prepare("INSERT INTO migration_id_map (migration_run_id,domain,source_table,source_id,target_table,target_id,action,metadata)
        SELECT ?,'billing',CASE i.legacy_source WHEN 'quotation' THEN 'ram_facturas' ELSE 'ram_facturas_controles' END,
        CONCAT(i.legacy_id,':',s.source_type,':',s.legacy_source_id),'invoice_sources',s.id,'mapped',
        JSON_OBJECT('legacy_invoice_id',i.legacy_id,'source_type',s.source_type,'legacy_source_id',s.legacy_source_id,'evidence_flags',s.evidence_flags)
        FROM invoice_sources s JOIN invoices i ON i.id=s.invoice_id ORDER BY s.id");$q->execute([$runId]);
    $q=$p->prepare("INSERT INTO migration_id_map (migration_run_id,domain,source_table,source_id,target_table,target_id,action)
        SELECT ?,'billing',CASE i.legacy_source WHEN 'quotation' THEN 'ram_facturas' ELSE 'ram_facturas_controles' END,i.legacy_id,'collections',c.id,'mapped'
        FROM collections c JOIN invoices i ON i.id=c.invoice_id ORDER BY c.id");$q->execute([$runId]);
    $q=$p->prepare("INSERT INTO migration_exceptions (migration_run_id,domain,source_table,source_id,code,severity,resolution,status,target_table,target_id)
        SELECT ?,'billing',CASE i.legacy_source WHEN 'quotation' THEN 'ram_facturas' ELSE 'ram_facturas_controles' END,i.legacy_id,
        CASE c.reconciliation_status WHEN 'ambiguous' THEN 'AMBIGUOUS_COLLECTION_MOVEMENT' ELSE 'UNRESOLVED_COLLECTION_MOVEMENT' END,
        'warning','Paid flag preserved; no unique bank movement demonstrated','preserved','collections',c.id
        FROM collections c JOIN invoices i ON i.id=c.invoice_id WHERE c.financial_movement_id IS NULL ORDER BY c.id");$q->execute([$runId]);
    $q=$p->prepare("INSERT INTO migration_exceptions (migration_run_id,domain,source_table,source_id,code,severity,resolution,status,target_table,target_id)
        SELECT ?,'billing','ram_facturas_controles',i.legacy_id,'GROUPED_INVOICE_WITHOUT_SOURCES','warning',
        'Grouped marker retained without inventing control zero','preserved','invoices',i.id FROM invoices i
        WHERE i.legacy_source='control' AND i.is_grouped=1 AND NOT EXISTS (SELECT 1 FROM invoice_sources s WHERE s.invoice_id=i.id)");$q->execute([$runId]);
    $q=$p->prepare("INSERT INTO migration_exceptions (migration_run_id,domain,source_table,source_id,code,severity,resolution,status,target_table,target_id)
        SELECT ?,'billing','ram_controles_vehiculares',c.legacy_id,'CONFLICTING_CONTROL_INVOICES','warning',
        'Both legacy directions preserved; no invoice selected by inference','preserved','vehicle_controls',c.id
        FROM vehicle_controls c JOIN (SELECT control_id FROM invoice_sources WHERE control_id IS NOT NULL GROUP BY control_id HAVING COUNT(DISTINCT invoice_id)>1) x ON x.control_id=c.id");$q->execute([$runId]);
    // Derivation invariants are checked against the already reconciled source projections.
    $checks=[
        "SELECT COUNT(*) FROM invoices i WHERE i.legacy_source='quotation' AND NOT EXISTS (SELECT 1 FROM invoice_sources s WHERE s.invoice_id=i.id AND s.source_type='quotation' AND s.legacy_source_id=i.legacy_quotation_id AND (s.quotation_id<=>i.quotation_id))",
        "SELECT COUNT(*) FROM invoices i WHERE i.legacy_source='control' AND i.legacy_control_id<>0 AND NOT EXISTS (SELECT 1 FROM invoice_sources s WHERE s.invoice_id=i.id AND s.source_type='control' AND s.legacy_source_id=i.legacy_control_id AND (s.evidence_flags & 1)=1)",
        "SELECT COUNT(*) FROM vehicle_controls c WHERE c.invoice_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM invoice_sources s WHERE s.invoice_id=c.invoice_id AND s.control_id=c.id AND (s.evidence_flags & 2)=2)",
        "SELECT COUNT(*) FROM invoice_sources s LEFT JOIN invoices i ON i.id=s.invoice_id WHERE i.id IS NULL",
        "SELECT COUNT(*) FROM invoice_sources s JOIN invoices i ON i.id=s.invoice_id WHERE s.source_type='quotation' AND (i.legacy_source<>'quotation' OR s.legacy_source_id<>i.legacy_quotation_id OR NOT(s.quotation_id<=>i.quotation_id))",
        "SELECT COUNT(*) FROM invoice_sources s LEFT JOIN vehicle_controls c ON c.id=s.control_id WHERE s.source_type='control' AND s.control_id IS NOT NULL AND (c.id IS NULL OR c.legacy_id<>s.legacy_source_id)",
        "SELECT COUNT(*) FROM invoice_sources s JOIN invoices i ON i.id=s.invoice_id WHERE s.source_type='control' AND (s.evidence_flags & 1)=1 AND (i.legacy_source<>'control' OR i.legacy_control_id<>s.legacy_source_id)",
        "SELECT COUNT(*) FROM invoice_sources s JOIN vehicle_controls c ON c.id=s.control_id WHERE (s.evidence_flags & 2)=2 AND NOT(c.invoice_id<=>s.invoice_id)",
        "SELECT COUNT(*) FROM invoices i LEFT JOIN collections c ON c.invoice_id=i.id WHERE i.is_paid=1 AND (c.id IS NULL OR c.claimed_amount<>i.total_amount OR NOT(c.paid_on<=>i.payment_date))",
        "SELECT COUNT(*) FROM collections c LEFT JOIN invoices i ON i.id=c.invoice_id WHERE i.id IS NULL OR NOT(i.is_paid<=>1)",
        "SELECT COUNT(*) FROM collections c WHERE c.financial_movement_id IS NOT NULL AND ((SELECT COUNT(*) FROM collection_movement_candidates x WHERE x.collection_id=c.id)<>1 OR (SELECT COUNT(*) FROM collection_movement_candidates x WHERE x.financial_movement_id=c.financial_movement_id)<>1)",
        "SELECT COUNT(*) FROM collections c WHERE c.financial_movement_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM collection_movement_candidates x WHERE x.collection_id=c.id AND x.financial_movement_id=c.financial_movement_id)",
    ];
    foreach($checks as $sql)if((int)$p->query($sql)->fetchColumn()!==0)throw new RuntimeException('BILLING_DERIVATION_RECONCILIATION_FAILED');
    $sourceCount=(int)$p->query('SELECT COUNT(*) FROM invoice_sources')->fetchColumn();$collectionCount=(int)$p->query('SELECT COUNT(*) FROM collections')->fetchColumn();
    $q=$p->prepare("SELECT COUNT(*) FROM migration_id_map WHERE migration_run_id=? AND target_table IN ('invoice_sources','collections')");$q->execute([$runId]);
    if((int)$q->fetchColumn()!==$sourceCount+$collectionCount)throw new RuntimeException('DERIVED_MAPPING_COUNT_FAILED');
    if(!validateDerivedBillingMaps($p,$runId))throw new RuntimeException('DERIVED_MAPPING_CONTENT_FAILED');
    return ['invoice_sources'=>$sourceCount,'collections'=>$collectionCount,'candidate_edges'=>(int)$p->query('SELECT COUNT(*) FROM collection_movement_candidates')->fetchColumn(),
        'collection_statuses'=>$p->query('SELECT reconciliation_status,COUNT(*) AS count FROM collections GROUP BY reconciliation_status ORDER BY reconciliation_status')->fetchAll()];
}
function billingRelations():array {
    $out=operationRelations();$defs=billingDefinitions();
    foreach(array_diff_key($defs,operationDefinitions()) as $d)foreach($d['relations']??[] as [$parent,$column])$out[]=[$d['target'],$column,$defs[$parent]['target']];
    return array_merge($out,[['vehicle_controls','invoice_id','invoices'],['invoices','quotation_id','quotations'],['invoices','client_id','clients'],['invoice_sources','invoice_id','invoices'],['invoice_sources','control_id','vehicle_controls'],['invoice_sources','quotation_id','quotations'],['collections','invoice_id','invoices'],['collections','financial_movement_id','financial_movements']]);
}
