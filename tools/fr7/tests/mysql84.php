<?php
declare(strict_types=1);
// Compatibility verification only. Never loads the real dump or connects production.
$verificationAdmin=null;$verificationLocked=false;$verificationCleanup=false;$verificationCode=1;$verificationStage='configuration';
$verificationSuite=getenv('FR7_TEST_SUITE')?:'fuel';
$verificationSchemas=['fr7_source_test','fr7_core_a_test','fr7_core_b_test','fr7_core_failure_test','fr7_ops_a_test','fr7_ops_b_test','fr7_fuel_a_test','fr7_fuel_b_test','fr7_fuel_engine_test'];
if($verificationSuite==='billing')$verificationSchemas=['fr7_source_test','fr7_core_a_test','fr7_core_b_test','fr7_core_failure_test','fr7_ops_a_test','fr7_ops_b_test','fr7_billing_a_test','fr7_billing_b_test','fr7_billing_failure_test','fr7_billing_schema_test','fr7_mapping_index_test'];
try {
    if(!in_array($verificationSuite,['fuel','billing'],true))throw new RuntimeException('Unknown suite');
    if(getenv('FR7_TEST_CREATE_FIXTURES')!=='YES')throw new RuntimeException('Explicit synthetic fixture opt-in required');
    $verificationPort=getenv('FR7_TEST_PORT')?:'3306';
    if(!preg_match('/^[0-9]+$/D',$verificationPort)||(int)$verificationPort<1||(int)$verificationPort>65535)throw new RuntimeException('Invalid local port');
    if(!extension_loaded('pdo_mysql'))throw new RuntimeException('PDO MySQL required');
    $verificationStage='connection';
    $verificationAdmin=new PDO("mysql:host=127.0.0.1;port={$verificationPort};charset=utf8mb4",getenv('FR7_TEST_USER')?:'root',getenv('FR7_TEST_PASS')?:'',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
    $verificationStage='engine_version';$verificationVersion=(string)$verificationAdmin->query('SELECT VERSION()')->fetchColumn();
    echo 'ENGINE='.$verificationVersion.PHP_EOL;
    if(!preg_match('/^8\.4\./D',$verificationVersion)||stripos($verificationVersion,'MariaDB')!==false)throw new RuntimeException('MySQL 8.4 required');
    $verificationStage='fixture_preflight';
    $verificationLocked=(int)$verificationAdmin->query("SELECT GET_LOCK('fr7:mysql84:synthetic-tests',0)")->fetchColumn()===1;
    if(!$verificationLocked)throw new RuntimeException('Another verification is running');
    foreach($verificationSchemas as $verificationSchema){
        $verificationQ=$verificationAdmin->prepare('SELECT COUNT(*) FROM information_schema.SCHEMATA WHERE SCHEMA_NAME=?');$verificationQ->execute([$verificationSchema]);
        if((int)$verificationQ->fetchColumn()!==0)throw new RuntimeException('Fixture schema already exists; nothing removed');
    }
    if((int)$verificationAdmin->query("SELECT COUNT(*) FROM mysql.user WHERE User='fr7_core_reader' AND Host='127.0.0.1'")->fetchColumn()!==0)throw new RuntimeException('Fixture user already exists; nothing removed');
    // Every reserved name was absent before this suite. Only these may be cleaned.
    $verificationCleanup=true;$verificationStage='tests';
    require __DIR__.($verificationSuite==='billing'?'/mapping-index-integration.php':'/fuel-integration.php');
    $verificationCode=0;
} catch(Throwable $verificationError) {
    fwrite(STDERR,'MYSQL84_VERIFY_FAILED; stage='.$verificationStage.PHP_EOL);
} finally {
    if($verificationCleanup&&$verificationAdmin instanceof PDO){
        try {
            foreach(array_reverse($verificationSchemas) as $verificationSchema)$verificationAdmin->exec('DROP DATABASE IF EXISTS `'.$verificationSchema.'`');
            $verificationAdmin->exec("DROP USER IF EXISTS 'fr7_core_reader'@'127.0.0.1'");
            echo 'CREATED_FIXTURES_CLEANED=YES'.PHP_EOL;
        } catch(Throwable $verificationCleanupError){$verificationCode=1;fwrite(STDERR,"FIXTURE_CLEANUP_FAILED; inspect reserved fr7_*_test schemas\n");}
    }
    if($verificationLocked&&$verificationAdmin instanceof PDO){
        try{$verificationAdmin->query("SELECT RELEASE_LOCK('fr7:mysql84:synthetic-tests')");}catch(Throwable $ignored){$verificationCode=1;}
    }
}
if($verificationCode===0)echo 'MYSQL84_VERIFICATION=PASSED; suite='.$verificationSuite.'; synthetic only; '.($verificationSuite==='billing'?111:68).' integration checks'.PHP_EOL;
exit($verificationCode);
