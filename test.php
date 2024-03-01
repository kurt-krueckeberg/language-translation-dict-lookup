<?php
declare(strict_types=1);

use Vocab\{CreateDBWordResultIterator, Pos, Config, FetchWord};

include 'vendor/autoload.php';

$c = new Config();

$cred = $c->get_db_credentials();

$pdo = new \PDO($cred["dsn"], $cred["user"], $cred["password"]);

$fetch = new FetchWord($pdo);
      
$row = $fetch('kommen'); 
 
$iter = new CreateDBWordResultIterator($pdo, $row);

//$iter = $creator->getIterator();

foreach($iter as $index => $value) {
    
    var_dump($value);
}

