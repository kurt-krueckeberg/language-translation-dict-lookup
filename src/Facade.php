<?php
declare(strict_types=1);
namespace Vocab;

use \SplFileObject as File;

class Facade {
   
   private FileReader $file;

   private SystranTranslator $sys;

   private AzureTranslator $azure;

   private Database $db;

   private LeipzigSentenceFetcher $sentFetcher;

   private Config $c;

   function __construct(string $filename, Config $c)
   {
      $this->file = new FileReader($filename);
      
      $this->sys = new SystranTranslator($c);

      $this->azure = new AzureTranslator($c);
     
      $this->db = new Database($c); 
  
      $this->sentFetcher = new LeipzigSentenceFetcher($c);

      $this->c = $c; 
   }
  
   /*
    * Returns list of words found in dictionary and inserted into database.
    */
   function db_insert() : array 
   {
      $results = [];
      
      try {
       
        foreach ($this->file as $word) {
           
           $iter = $this->sys->lookup($word, 'de', 'en');
    
           if (!$iter->valid()) {
               
                echo "$word has no definitions\n";
                     
                continue;
           }
      
           //--echo "$word results:\n";

           foreach ($iter as $lookup_result)  {
               
              $word = $lookup_result->word_defined();
              
              $results[] = $word;
                 
              if ($this->db->word_exists($word)) {

                  echo "$word is already in database.\n";
                  continue;
              }
                    
              $this->db->save_lookup($lookup_result);

              echo "$word saved to database.\n";
              echo "Getting samples for: $word.\n";
    
              $sentIter = $this->sentFetcher->fetch($word);  

              if ($sentIter !== false) {
                  
                 $this->db->save_samples($word, $this->azure, $sentIter);
                 echo "Samples for $word saved to database.\n";

              } else {

                 echo "No Samples found for $word.\n";
              }

           }
        }                
      } catch (\PDOException $e) {
      
            echo "Exception: message = " . $e->getMessage() . "\nError Code = " . $e->getCode() . "\n";

      } catch (\Exception $e) {
      
            echo "Exception: message = {$e->getMessage()}.\nError Code = {$e->getCode()}.\nException code = {$e->getCode()}.\n";
      }
      
      return $results;
   } 
 
   private function insert_samples(string $word) : bool
   { 
      $sent_iter = $this->sentFetcher->fetch($word, $this->c->sentence_count());
      
      if ($sent_iter == false) { 
          
           echo "No sample sentences available for '$word'\n";
           return false;
      }

      $this->db->save_samples($word, $sent_iter);  
   }

   function create_html(array $words, string $filename) : void
   {
      $this->html = new BuildHtml($filename, "de", "en");

      foreach($words as $word) {

        $resultIter = $this->db->fetch_db_word($word);  
        
        if ($resultIter === false) {
            
            echo "$word is not in database.\n";
            continue;            
        }
        
        // Someh
        foreach($resultIter as $dbword) {
            
          $cnt = $this->html->add_definitions($dbword); 
 
          $sentIter = $this->db->fetch_samples($dbword->get_word_id()); 
  
          $cnt = $this->html->add_samples($word, $sentIter, $this->azure); 
        }
     }
   }
 }
