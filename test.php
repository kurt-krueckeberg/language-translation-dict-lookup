<?php
declare(strict_types=1);
use \SplFileObject as File;

use Vocab\{FileReader, Config, Vocab};

include 'vendor/autoload.php';
  
$c = new Config();
         
if (!file_exists($c->lookup_file())) {
     
    die($c->lookup_file() . " not found.\n");
}

function fetch_words(string $fileName) : array
{
 $words = [];

 $file = new FileReader($c->lookup_file(), "r");

  foreach ($file as $word) 
     $words[] = $word;
 
  return $words;
}

try {

  $fac = new Vocab($c);

  $words = fetch_words($c->lookup_file());
    
  $words_inserted = $fac->db_insert($words);

  $fac->create_html($words_inserted, 'output');

  $file_put_contents("words_inserted.txt", implode("\n", $words_inserted));

  echo "See 'log.txt' and 'words_inserted.txt.\n";

} catch (\Exception $e) {

  echo $e->getMessage();

  echo "-------------\n";
  echo "getting Trace as String: \n";
  echo $e->getTraceAsString();
}
