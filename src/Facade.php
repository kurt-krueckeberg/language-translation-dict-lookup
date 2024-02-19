<?php
declare(strict_types=1);
namespace Vocab;

use \SplFileObject as File;

class Facade {
   
   private FileReader $file;

   private SystranTranslator $sys;

   private Database $db;

   private LeipzigSentenceFetcher $sentFetcher;

   private Config $c;

   function __construct(Config $c)
   {
      $this->file = new FileReader($c->lookup_file());
      
      $this->sys = new SystranTranslator($c);
     
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
      
           echo "$word results:\n";

           foreach ($iter as $lookup_result)  {
               
              $word = $lookup_result->word_defined();
              
              $results[] = $word;
                 
              if ($this->db->word_exists($word)) {

                  echo "$word is already in database.\n";
                  continue;
              }
                    
              $this->db->save_lookup($lookup_result);
    
              $sentIter = $this->sentFetcher->fetch($word);  

              if ($sentIter !== false) {
                  
                 $this->db->save_samples($word, $this->sys, $sentIter);
              }

              echo "$word saved to database.\n";
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

   // Fetch words and their definitions, pos, inflections, etc from database, and for those words found, create a web page
   function create_html(array $words, string $filename) : void
   {
      $this->html = new BuildHtml($filename, "de", "en");

      foreach($words as $word) {

        $result = $this->db->fetch_word($word);  
        
        if ($result === false) {
            
            echo "$word is not in database.\n";
            continue;            
        }
        
        list($wrface, $word_id) = $result;
   
        $cnt = $this->html->add_definitions($wrface); 
 
        $sentIter = $this->db->fetch_samples($word_id); 
  
        $cnt = $this->html->add_samples($word, $sentIter, $this->sys); 
     }
   }
 }
