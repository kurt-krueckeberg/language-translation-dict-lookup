<?php
declare(strict_types=1);
use \SplFileObject as File;

use Vocab\{FileReader, Config, Vocab};

include 'vendor/autoload.php';
  
$c = new Config();
         
if (!file_exists($c->lookup_file())) {
     
    die($c->lookup_file() . " not found.\n");
}

try {

  $fac = new Vocab($c);

  $words = $c->fetch_words();
    
  $words_inserted = $fac->db_insert($words);

} catch (\Exception $e) {

  echo $e->getMessage();

  echo "-----\ngetting Trace as String: \n";
  echo $e->getTraceAsString();
}
