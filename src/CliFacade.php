<?php
declare(strict_types=1);
namespace Vocab;

use \SplFileObject as File;

class CliFacade {
   
   private CliDatabase $db;

   private Config $c;

   function __construct(Config $c)
   {
      $this->c = $c; 
   }

   public function __invoke(string $outfile)  
   { 
      $this->html = new BuildHtml($filename, "de", "en");

      while (false !== $word = readline())  {
            
          if ($this->db->word_exists($word)) {

              echo "$word is already in database.\n";
              continue;
          }
                
          // Fetch all prefix-family verbs by default.
          $results = $this->fetch_lookup_results($word);
         
          $this->htmlAdd($results);  
      }
   }

   // Fetch words and their definitions, pos, inflections, etc from database, and for those words found, create a web page
   function htmlAdd(array $results) : void
   {
      foreach($results as $wrface) {

        $cnt = $this->html->add_definitions($wrface); 
 
        $sentIter = $this->db->fetch_samples($word_id); 
  
        $cnt = $this->html->add_samples($word, $sentIter, $this->azure); 
     }
   }
 }
