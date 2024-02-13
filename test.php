#!/usr/bin/env php
<?php
declare(strict_types=1);
use \SplFileObject as File;

use Vocab\{FileReader, Config, Facade};

include 'vendor/autoload.php';
   
 $c = new Config();
         
 if (!file_exists($c->lookup_file())) {
     
     die($c->lookup_file() . " not found.\n");
 }
 
 $cred = $config->get_db_credentials();
     
 $pdo = new \PDO($cred["dsn"], $cred["user"], $cred["password"]); 
 
 $fac = new Vocab\\NewDBVerb($pdo);

 //$fac->db_insert();

 //--$fac->create_html('output');
