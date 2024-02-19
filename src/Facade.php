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
  
   function db_insert() : bool
   {
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
                 
              if ($this->db->word_exists($word)) {

                  echo "$word is already in database.\n";
                  continue;
              }
                    
              $this->db->save_lookup($lookup_result);
    
              $sentIter = $this->sentFetcher->fetch($word);  

              if ($sentIter !== false) {
                  
                 $this->db->save_samples($word, $sentIter);
              }

              echo "$word saved to database.\n";
           }
        } 

      } catch (PDOException $e) {
      
            echo "Exception: message = " . $e->getMessage() . "\nError Code = " . $e->getCode() . "\n";

      } catch (Exception $e) {
      
            echo "Exception: message = {$e->getMessage()}.\nError Code = {$e->getCode()}.\nException code = {$e->getCode()}.\n";
      }
      return true;
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
   function create_html(string $filename) : void
   {
      $this->html = new BuildHtml($filename, "de", "en");

      foreach($this->file as $word) {

        list($wrface, $word_id) = $this->db->fetch_word($word);
   
        $cnt = $this->html->add_definitions($wrface); 
 
        $sentIter = $this->db->fetch_samples($word_id); 
  
        $cnt = $this->html->add_samples($word, $sentIter, $this->sys); 
     }
   }
 }
