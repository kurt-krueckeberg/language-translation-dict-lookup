<?php
declare(strict_types=1);
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
  
   function db_insert() int
   {
      try {
       
        foreach ($file as $word) {
           
           $iter = $this->sys->lookup($word, 'de', 'en');
    
           if (count($iter) == 0) {
               
                echo "$word has no definitions\n";
                     
                continue;
           }
           
           echo "$word results:\n";

           foreach ($iter as $lookup_result)  {
               
              $word = $lookup_result->word_defined();
                 
              if (!$this->db->word_exists($word)) {
                    
                  $this->db->save_lookup($lookup_result);
    
                  echo "$word saved to database.\n";
              }
             
             $this->insert_samples($word);
           }
        } 

      } catch (PDOException $e) {
      
            echo "Exception: message = " . $e->getMessage() . "\nError Code = " . $e->getCode() . "\n";

      } catch (Exception $e) {
      
            echo "Exception: message = {$e->getMessage()}.\nError Code = {$e->getCode()}.\nException code = {$e->getCode()}.\n";
      }
   } 
 
   private function insert_samples(string $word)
   { 
      $sent_iter = $sentFetcher->fetch($word, $c->sentence_count());
      
      if ($sent_iter == false) { 
          
           echo "No sample sentences available for '$word'\n";
           continue;
      }
   
      $db->save_samples($word, $sent_iter);  
   }

    // Read data from database for those words found and create web page
    function create_html() : void
    {
 
    
    }
 }
