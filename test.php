<?php
declare(strict_types=1);
use \SplFileObject as File;

use Vocab\{FileReader, Config, Vocab, SystranTranslator};
use GuzzleHttp\{Psr7, Exception\ClientException};

include 'vendor/autoload.php';
  
$c = new Config();
         
if (!file_exists($c->lookup_file())) {
     
    die($c->lookup_file() . " not found.\n");
}

 //$words = ['ankommen', 'ansehen', 'kommen', 'sehen', 'kam', 'kamen', 'sah', 'sahen']; 
 $words = ['sah', 'sahen']; 

   $dict = new SystranTranslator($c);

   foreach ($words as $word) {

      $results = $dict->lookup($word, 'de', 'en');

     if ($results->valid() === false) {

        echo "There are no results for $word.\n";

     }  else {

        foreach($results as $result) {
            
           $pos =  $result->get_pos()->value;
           
           $word = $result->word_defined();
           
           $lemma =  $result->get_lemma();
           
           echo "'" . $word . "' is the word we want to define. It was of POS: ". $pos . ". The actual 'lemma' returned was: " . $lemma . "\n";  
        }
     }
   }
