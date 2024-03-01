<?php
declare(strict_types=1);

use Vocab\{CreateDBWordResultIterator, Pos, Config};

include 'vendor/autoload.php';

$c = new Config();

$cred = $c->get_db_credentials();

$pdo = new \PDO($cred["dsn"], $cred["user"], $cred["password"]);

$fetch = $new FetchWord($pdo);
      
$row = $fetch('kommen'); 
 
$creator = CreateDBWordResultIterator($this->pdo, $row);

