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

  $fac->create_html($words_inserted, 'output');

  file_put_contents("words-inserted.txt", implode("\n", $words_inserted));

} catch (\Exception $e) {

  echo $e->getMessage();

  echo "-----\ngetting Trace as String: \n";
  echo $e->getTraceAsString();
}
