<?php
declare(strict_types=1);
namespace Vocab;

use \SplFileObject as File;

/*
 * Facade - top-level interface object.
 */
class Vocab {
   
   private DictionaryInterface $dictionary; // Used for definition lookup

   private TranslateInterface $translator;  // Used for translation of text

   private Database $db;

   private LeipzigSentenceFetcher $sentFetcher;

   private array $c;

   private File $logFile;

   private \PDO $pdo;

   private MessageLog $log;

   private RateLimitGauge $rate_limit;   

   function __construct(array $config)
   {
      $this->c = $config;
            
      $translationClass = $config['namespace'] . '\\' . $config['providers'][ $config['implementors']['translator'] ]['class'];
      
      $dictionaryClass = $config['namespace'] . '\\' . $config['providers'][ $config['implementors']['dictionary'] ]['class'];
      
      $this->translator = new $translationClass($config);
      
      $this->dictionary = new $dictionaryClass($config);
     
      $this->pdo = new \PDO($config['database']["dsn"], $config['database']["user"], $config['database']["password"]); 
     
      $this->db = new Database($this->pdo, $config['language']['locale']); 
  
      $this->sentFetcher = new LeipzigSentenceFetcher($config);

      $provider_name = ProviderID::azure->name; // TODO: Correct. This is hardcoded!!!!!!!!
      
      $this->rate_limit = new RateLimitGauge($config['providers'][$provider_name]['limit']);
            
      $this->logFile = new File("results.log", "w");

      $this->log = new MessageLog($this->logFile);
   }
  
   function display_log()
   {
       $this->log->report();
   }
   
   
   function db_insert(string $word) : void
   {
      $results = [];
      
      try {

           if ($this->db->word_exists($word)) {

              $this->log->log("$word is already in database.");
              return;
           }
           
           $this->pdo->beginTransaction();
 
           $r = $this->db_insert_word($word, $this->log);

           $results = \array_merge($results, $r);
       
           $this->log->report(); // Display all messages
            
           $this->log->reset();       
        
      } catch (\Exception $e) {
      
          echo  "Exception: {$e->getMessage()}.\nError Code = {$e->getCode()}.\nException code = {$e->getCode()} \n";
          echo  "Exception trace: {$e->getTraceAsString()} \n";
      }

      echo "Results of words inserted is in: results.log.\n";      

      $this->pdo->commit();

      return;
   }
   
   /*
    * Returns list of words found in dictionary and inserted into database.
    */
   function db_insert_word(string $word, MessageLog $log) : array
   {   
      $iter = $this->dictionary->lookup($word, 'de', 'en');
 
      if (!$iter->valid()) {
          
           $log->log("$word has no definitions");
                
           return array();
      }

      $results = [];
   
      foreach ($iter as $result)  {
           
          $word_defined = $result->word_defined();

          if ($word_defined != $word) {
              
              $this->log->log("$word not in dictionary, but $word_defined was found.");

              continue;
          }
          
          $results[] = $word_defined;
          
          $this->insertdb_lookup_result($result, $log);          
      }
   
      return $results;
   } 
 
   private function insertdb_lookup_result(WordInterface $lookup_result, MessageLog $log) : void
   {               
       $word = $lookup_result->word_defined();
       
       if ($this->db->word_exists($word)) {

           $log->log("$word is already in database.");
           return;
       }


       $this->db->save_lookup($lookup_result);

       $log->log("$word saved to database.");

       $this->insertdb_samples($word, $log);
    }

   function save_samples(string $word, \Traversable $sentences_iter) : bool
   {
      foreach ($sentences_iter as $sentence)  {

           // Will this exceed the rate limit 
          if (($this->rate_limit)($text) === false) {

           $msg =  "Azure Text Translate hourly character limit will be exceeded. Waiting...." . self::$rate_limit->wait_time() . "\n";

           $this->log($msg);

            echo $msg;

            $this->rate_limit->wait(); // Do we want to wait?
         }

         $trans = $this->translator->translate($sentence, 'en', 'de'); 
         
         $this->db->save_sample($sentence, $trans, $prim_key);                  
      }
      
      return true;
   }

   private function insertdb_samples(string $word, MessageLog $log) : bool
   { 
      $sentences_iter = $this->sentFetcher->fetch($word, $this->c['samples_count']);
      
      if ($sentences_iter == false) { 
          
           $log->log("No sample sentences available for '$word'.");
           return false;
      }

      foreach ($sentences_iter as $sentence)  {

        if (($this->rate_limit)($sentence) == false) {

           $msg =  "Azure hourly character limit will be exceeded. Waiting to translate sample sentences for $word...." . ($this->rate_limit)->wait_time() . "\n";

           $log($msg);

           echo $msg;

           $this->rate_limit->wait(); 
        }

        $translation = $this->translator->translate($sentence, 'en', 'de'); 

        $this->db->save_sample($word, $sentence, $translation);  

      }

      return true;
   }
   
   function fetch_db_word(string $word, bool $exact_match=false) : \Iterator | false
   {
       return $this->db->fetch_db_word($word);
   }

   function create_html(array $words, string $filename) : array
   {
      $words_added = [];
       
      $this->html = new HtmlBuilder($filename, "de", "en");

      foreach($words as $word) {

        $resultIter = $this->db->fetch_db_word($word);  
        
        if ($resultIter === false) {
            
            $this->log->log("$word is not in database.");;
            continue;            
        }
       
        foreach($resultIter as $dbword) {
            
          $words_added[] = $word;  
          
          $this->html->add_definitions($dbword); 
 
          $sentIter = $this->db->fetch_db_samples($dbword->get_word_id()); 
  
          $this->html->add_samples($word, $sentIter);
          
        }
     }
     return $words_added;
   }
 }
