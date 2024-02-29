<?php
declare(strict_types=1);
use \SplFileObject as File;

use Vocab\{FileReader, Config, Facade};

include 'vendor/autoload.php';
  
$c = new Config();
         
if (!file_exists($c->lookup_file())) {
     
    die($c->lookup_file() . " not found.\n");
}

$fac = new Facade($c->lookup_file(), $c);

//$words = $fac->db_insert();

$file = new FileReader("/home/kurt/vocab-german/vocab.txt", "r");

$words = [];

foreach($file as $word) {
    $words[] = $word;
}
 
$fac->create_html($words, 'output');
