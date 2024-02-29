<?php
declare(strict_types=1);

use Vocab\{CreateDBWordResultIterator, Pos, Config};

include 'vendor/autoload.php';

$c = new Config();

$cred = $c->get_db_credentials();

$pdo = new \PDO($cred["dsn"], $cred["user"], $cred["password"]); 

$x = new CreateDBWordResultIterator($pdo, Pos::Verb, 1);
