#!/usr/bin/env php
<?php
declare(strict_types=1);
use \SplFileObject as File;

use Vocab\{Database, SystranTranslator, LeipzigSentenceFetcher, DbTablesMediator, FileReader, Config, ProviderID, Word};

include 'vendor/autoload.php';

if ($argc != 2) {

  echo "Enter the vocabulary words input file.\n";
  return;

} else if (! file_exists($argv[1]))  {

  echo "Input file does not exist.\n";
  return;
}

try {
    
    $fwords = $argv[1];
 
    $file = new FileReader($fwords);
    
    $c = new Config();
    
    $sys = new SystranTranslator($c);
   
    $db = new Database($c); 
    
    foreach ($file as $word) {
       
       $iter = $sys->lookup($word, 'de', 'en');

       if (count($iter) == 0) {
           
            echo "$word has no definitions\n";
                 
            continue;
       }
       
       echo "$word results:\n";
       
       foreach ($iter as $result)  {
           
            $word = $result->word_defined();
               
            if (!$db->word_exists($word)) {
                  
                $db->save($result);

                echo "$word saved to database.\n";
            }
        }       
    }

} catch (PDOException $e) {

      echo "Exception: message = " . $e->getMessage() . "\nError Code = " . $e->getCode() . "\n";
}
catch (Exception $e) {

      echo "Exception: message = {$e->getMessage()}.\nError Code = {$e->getCode()}.\nException code = {$e->getCode()}.\n";
} 
