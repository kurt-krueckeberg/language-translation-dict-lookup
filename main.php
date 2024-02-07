#!/usr/bin/env php
<?php
declare(strict_types=1);
use \SplFileObject as File;

use Vocab\{Database, SystranTranslator, LeipzigSentenceFetcher, DbTablesMediator, FileReader, Config, ProviderID, Word};

include 'vendor/autoload.php';

try {
    
    $c = new Config();
            
    if (!file_exists($c->lookup_file())) {
        
        die($c->lookup_file() . " not found.\n");
    }

    $file = new FileReader($c->lookup_file());
    
    $sys = new SystranTranslator($c);
   
    $db = new Database($c); 

    $sentFetcher = new LeipzigSentenceFetcher($c);
    
    foreach ($file as $word) {
       
       $iter = $sys->lookup($word, 'de', 'en');

       if (count($iter) == 0) {
           
            echo "$word has no definitions\n";
                 
            continue;
       }
       
       echo "$word results:\n";
       
       foreach ($iter as $lookup_result)  {
           
          $word = $lookup_result->word_defined();
             
          if (!$db->word_exists($word)) {
                
              $db->save_lookup($lookup_result);

              echo "$word saved to database.\n";
          }

          $sent_iter = $sentFetcher->fetch($word, $c->sentence_count());
          
          if ($sent_iter == false) { 
              
               echo "No sample sentences available for '$word'\n";
               continue;
          }

          $db->save_samples($word, $sent_iter);  
        }       
    }

} catch (PDOException $e) {

      echo "Exception: message = " . $e->getMessage() . "\nError Code = " . $e->getCode() . "\n";
}
catch (Exception $e) {

      echo "Exception: message = {$e->getMessage()}.\nError Code = {$e->getCode()}.\nException code = {$e->getCode()}.\n";
}
