<?php
declare(strict_types=1);
use \SplFileObject as File;

use Vocab\{Database, SystranTranslator, LeipzigSentenceFetcher, DbTablesMediator, FileReader, Config, ProviderID, Word};

include 'vendor/autoload.php';

function insert_word(string $word)
{
    $iter = $sys->lookup($word, 'de', 'en');

    if (!$iter->valid()) {
        
         echo "$word has no definitions\n";
              
         return;
    }
    
    echo "$word results:\n";
    
    foreach ($iter as $lookup_result)  {
        
       $word = $lookup_result->word_defined();
          
       if (!$db->word_exists($word)) {
             
           $db->save_lookup($lookup_result);

           echo "$word saved to database.\n";
       }
    }       
}

try {
    
    $c = new Config();
            
    if (!file_exists($c->lookup_file())) {
        
        die($c->lookup_file() . " not found.\n");
    }

    $sys = new SystranTranslator($c);
   
    $db = new Database($c); 

    $sentFetcher = new LeipzigSentenceFetcher($c);
    
    $words = $c->fetch_words();
    
    foreach($words as $verb) {

       insert_word($verb);
    }

} catch (PDOException $e) {

      echo "Exception: message = " . $e->getMessage() . "\nError Code = " . $e->getCode() . "\n";
}
catch (Exception $e) {

      echo "Exception: message = {$e->getMessage()}.\nError Code = {$e->getCode()}.\nException code = {$e->getCode()}.\n";
}
