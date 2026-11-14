<?php
declare(strict_types=1);

require __DIR__.'/maintenance-integration.php';

foreach (array_keys(inventoryTables()) as $table) {
    if (!preg_match('/CREATE TABLE `'.preg_quote($table,'/').'`[\s\S]*?;/', $baseline, $match)) {
        throw new RuntimeException('Missing inventory fixture schema');
    }
    $a->exec(str_replace('ENGINE=MyISAM','ENGINE=InnoDB',$match[0]));
}

insertFixture($a,'ram_inventariosmateriales',['id'=>1,'nombre'=>'Part','descripcion'=>'','precio'=>'2.50','valor'=>'12.50','existencia'=>5]+$ts);
insertFixture($a,'ram_inventariosmaterialesentradas',['id'=>1,'inventariomaterial_id'=>1,'cantidad'=>7,'precio'=>'2.50','observaciones'=>'']+$ts);
insertFixture($a,'ram_inventariosmaterialessalidas',['id'=>1,'inventariomaterial_id'=>1,'unidad_id'=>9,'cantidad'=>2,'observaciones'=>'']+$ts);
foreach ([1,2] as $id) {
    insertFixture($a,'ram_llantas',['id'=>$id,'clave'=>'DUP','marca'=>'','medida'=>'','tipo'=>'','existencia'=>0]+$ts);
}
insertFixture($a,'ram_llantassalidas',['id'=>1,'llanta_id'=>1,'unidad_id'=>9,'cantidad'=>2,'observaciones'=>'']+$ts);
insertFixture($a,'ram_llantasentradas',['id'=>1,'llanta_id'=>99,'cantidad'=>7,'precio'=>'0.00','observaciones'=>'']+$ts);

$start=$count;
$a->exec('CREATE DATABASE fr7_inventory_test');
putenv('FR7_TARGET_DB=fr7_inventory_test');
coreCheck(main(['migrate.php','--dry-run','--inventory'])===0,'inventory dry run');
coreCheck((int)$a->query("SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA='fr7_inventory_test'")->fetchColumn()===0,'inventory dry run leaves target empty');
coreCheck(main(['migrate.php','--execute','--inventory'])===0,'inventory execute');
$target=connectDatabase('FR7_TARGET');
$metrics=json_decode($target->query('SELECT metrics FROM migration_runs')->fetchColumn(),true,512,JSON_THROW_ON_ERROR);
coreCheck($metrics['inventory_stock']['inventory_materials']['stock_differences']===0,'material stock reconciles');
$tires=$metrics['inventory_stock']['inventory_tires'];
coreCheck($tires['items']===2 && $tires['stock_differences']===1 && $tires['negative_reconstructed_stock']===1,'duplicate tires and negative history preserved');
$receipt=$target->query('SELECT tire_id,legacy_tire_id FROM inventory_tires_receipts')->fetch();
coreCheck($receipt['tire_id']===null && (int)$receipt['legacy_tire_id']===99,'orphan tire movement preserved');
echo 'INVENTORY INTEGRATION PASSED: '.($count-$start).' additional checks'.PHP_EOL;
