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

  $words = [];

  $file = new FileReader($c->lookup_file(), "r");

  foreach ($file as $word) 
     $words[] = $word;
  
  $words = $fac->db_insert($words);

  $fac->create_html($words, 'output');

} catch (\Exception $e) {

  echo $e->getMessage();

  echo "getting Trace: \n";
  $trace = $e->getTrace();

  print_r($trace); 

  echo "-------------\n";
  echo "getting Trace as String: \n";
  echo $e->getTraceAsString();
}
