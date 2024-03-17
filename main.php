<?php
declare(strict_types=1);
use \SplFileObject as File;

use Vocab\{FileReader, Config, Vocab};
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ClientException;

include 'vendor/autoload.php';
  
$c = new Config();
         
if (!file_exists($c->lookup_file())) {
     
    die($c->lookup_file() . " not found.\n");
}

try {

  $fac = new Vocab($c);

  $words = $c->fetch_words();

  try {
    
  $words_inserted = $fac->db_insert($words); // TODO: Add the exception logic to db_inset($word)  and  insert_samples--or whatever it is called?

  $fac->create_html($words_inserted, 'output');
  
  file_put_contents("words-inserted.txt", implode("\n", $words_inserted));

 } catch (ClientException $e) {  // catch 400 and 500 http erros thrown by Guzzle.

      echo Psr7\Message::toString($e->getRequest());
      echo Psr7\Message::toString($e->getResponse());
 
} catch (\Exception $e) {

  echo $e->getMessage();

  echo "-----\ngetting Trace as String: \n";
  echo $e->getTraceAsString();
}
