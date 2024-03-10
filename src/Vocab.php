<?php
declare(strict_types=1);
namespace Vocab;

use \SplFileObject as File;

/*
 * This is a facade class, top-level interface coordinator object.
 */
class Vocab {
   
   private SystranTranslator $dictiontary; // Used for definition lookup

   private AzureTranslator $azure;  // Used for translation of text

   private Database $db;

   private LeipzigSentenceFetcher $sentFetcher;

   private Config $c;

   private File $logFile;

   function __construct(Config $c)
   {
      $this->dictionary = new SystranTranslator($c);

      $this->translator = new AzureTranslator($c);
     
      $this->db = new Database($c); 
  
      $this->sentFetcher = new LeipzigSentenceFetcher($c);

      $this->c = $c; 
      
      $this->logFile = new File("results.log", "w");
   }
  
   function db_insert(array $words) : array
   {
      $results = [];

      $log = new MessageLog($this->logFile);
      
      try {
      
        foreach($words as $word) {
               
           $r = $this->db_insert_word($word, $log);

           $results = \array_merge($results, $r);
       
           $log->report(); // Display all messages
            
           $log->reset();       
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
             
       $this->db->save_lookup($lookup_result);

       $log->log("$word saved to database.");

       $this->insertdb_samples($word, $log);
   }

   private function insertdb_samples(string $word, MessageLog $log) : bool
   { 
      // Skip the 1st space, if present, returning the remainder, ie, the 2nd word. 
      $get_word = function(string $word) : string {

         return (($pos = \strpos($word, " ")) !== false) ? substr($word, $pos + 1) : $word;
      };
       
      $sent_iter = $this->sentFetcher->fetch($get_word($word), $this->c->sentence_count());
      
      if ($sent_iter == false) { 
          
           $log->log("No sample sentences available for '$word'.");
           return false;
      }

      return $this->db->save_samples($word, $this->translator, $sent_iter);  
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
