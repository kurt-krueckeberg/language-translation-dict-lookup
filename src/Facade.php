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
  
   function db_insert() : int
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
 
   private function insert_samples(string $word) : bool
   { 
      $sent_iter = $sentFetcher->fetch($word, $c->sentence_count());
      
      if ($sent_iter == false) { 
          
           echo "No sample sentences available for '$word'\n";
           return false;
      }

      $this->db->save_samples($word, $sent_iter);  
   }

    // Fetch words and their definitions, pos, inflections, etc from database, and for those words found, create a web page
    function create_html(FileReader $file) : void
    {
       foreach($file as $word) {

        $html = new BuildHtml(/*$argv[2]*/ "output", "de", "en");
  
        $cnt = $html->add_definitions(???$iter); 
 
        $cnt = $html->add_samples($word, $iter, $sys); 
    }
 }
