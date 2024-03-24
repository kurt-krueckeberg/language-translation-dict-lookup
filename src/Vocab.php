<?php
declare(strict_types=1);
namespace Vocab;

use \SplFileObject as File;

/*
 * Facade - top-level interface object.
 */
class Vocab {
   
   private SystranTranslator $dictiontary; // Used for definition lookup

   private TranslateInterface $translator;  // Used for translation of text

   private Database $db;

   private LeipzigSentenceFetcher $sentFetcher;

   private Config $c;

   private File $logFile;

   private \PDO $pdo;

   private MessageLog $log;

   private RateLimitGauge $rate_limit;

   private $s1_hourly_limit = 2000000; // This should go in Config.php

   function __construct(Config $config)
   {
      $this->dictionary = new SystranTranslator($config);

      $this->translator = new AzureTranslator($config);

      $cred = $config->get_db_credentials();
     
      $this->pdo = new \PDO($cred["dsn"], $cred["user"], $cred["password"]); 
     
      $this->db = new Database($this->pdo, $config->get_locale()); 
  
      $this->sentFetcher = new LeipzigSentenceFetcher($config);

      $this->c = $config; 
      
      $this->logFile = new File("results.log", "w");

      $this->log = new MessageLog($this->logFile);

      $this->rate_limit = new RateLimitGauge($config['config']['providers']['azure']['limit']);
   }
  
   function db_insert(array $words) : array
   {
      $results = [];
      
      try {
      
        foreach($words as $word) {
               
           $r = $this->db_insert_word($word, $this->log);

           $results = \array_merge($results, $r);
       
           $this->log->report(); // Display all messages
            
           $this->log->reset();       
        }
        
      } catch (\Exception $e) {
      
          echo  "Exception: {$e->getMessage()}.\nError Code = {$e->getCode()}.\nException code = {$e->getCode()} \n";
          echo  "Exception trace: {$e->getTraceAsString()} \n";
      }

      echo "Results of words inserted is in: results.log.\n";      
      return $results;             
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

       $this->pdo->beginTransaction();

       $this->db->save_lookup($lookup_result);

       $log->log("$word saved to database.");

       $this->insertdb_samples($word, $log);

       $this->pdo->commit();
   }

   function save_samples(string $word, \Traversable $sentences_iter) : bool
   {
      foreach ($sentences_iter as $sentence)  {

           // Will this exceed the rate limit 
          if ($this->$rate_limit($text) == false) {

           $msg =  "Azure hourly character limit will be exceeded. Waiting...." . self::$rate_limit->wait_time() . "\n";

           $this->log($msg);

            echo $msg;

            $this->rate_limit->wait(); // Do we want to wait?
         }

         $trans = $translator->translate($sentence, 'en', 'de'); 
         
         $this->db->save_sample($sentence, $trans, $prim_key);                  
      }
      
      return true;
   }

   private function insertdb_samples(string $word, MessageLog $log) : bool
   { 
      $sent_iter = $this->sentFetcher->fetch($word, $this->c->sentence_count());
      
      if ($sent_iter == false) { 
          
           $log->log("No sample sentences available for '$word'.");
           return false;
      }

      foreach ($sentences_iter as $sentence)  {

        if ($this->$rate_limit($text) == false) {

           $msg =  "Azure hourly character limit will be exceeded. Waiting to translate sample sentences for $word...." . self::$rate_limit->wait_time() . "\n";

           $log($msg);

           echo $msg;

           $this->rate_limit->wait(); 
        }

        $translation = $translator->translate($sentence, 'en', 'de'); 

        $this->db->save_sample($word, $sentence, $translation);  

      }

      return true;
   }

   function create_html(array $words, string $filename) : void
   {
      $this->html = new BuildHtml($filename, "de", "en");

      foreach($words as $word) {

        $resultIter = $this->db->fetch_db_word($word);  
        
        if ($resultIter === false) {
            
            $this->log->log("$word is not in database.");;
            continue;            
        }
       
        foreach($resultIter as $dbword) {
            
          $cnt = $this->html->add_definitions($dbword); 
 
          $sentIter = $this->db->fetch_samples($dbword->get_word_id()); 
  
          $this->html->add_samples($word, $sentIter); 
        }
     }
   }
 }
