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


 $fac = new Vocab($c);

 $words = $c->fetch_words();

 try {

 /*
  RestApi now thwrows 400 and 500 http errors. Its request() method will print the meaning of these exceptions to stderr.
  Based on what gets thrown, I can decide if I really have a coding error or I need a differenet Azure payment plan.
  */
 $words_inserted = $fac->db_insert($words); 

 $fac->create_html($words_inserted, 'output');
 
 file_put_contents("words-inserted.txt", implode("\n", $words_inserted));

}  catch (\Exception $e) {

   echo $e->getMessage();

   echo "-----\ngetting Trace as String: \n";
   echo $e->getTraceAsString();
 }
