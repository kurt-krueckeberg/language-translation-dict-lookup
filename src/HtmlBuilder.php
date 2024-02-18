<?php
declare(strict_types=1);
namespace Vocab;

class HtmlBuilder {
    
   public function __construct()
   { 
   }

   public function build_definitions_section(WordInterface $wrface) : string
   {
      static $sec_start =  "<section class='definitions'>\n";
      static $dl_start = "  <dl class='hwd'>\n";

      static $fmt = "  <dt>\n   <ul>\n    <li>%s</li>\n    <li class='pos'>%s</li>\n   </ul>\n  </dt>\n";    

      $sec = $sec_start;
 
      $dl = $dl_start;

      $dl .= sprintf($fmt, $wrface->word_defined(), strtoupper($wrface->get_pos()->getString()));

      $defns = $this->add_defn($wrface); 
          
      $dl .= $defns . " </dl>\n";

      $sec .= $dl;
             
      $sec .= "</section>\n";

      return $sec;   
   } 

   private function add_defn(\Iterator $defnsIter) : string
   {       
      $dds = '';
      static $defn_fmt =  "  <dd>%s</dd>\n";
      static $exp_fmt =  "    <dt>%s</dt>\n    <dd>%s</dd>\n";

      foreach ($defnsIter as $defn => $expressions) {

         $dds .= sprintf($defn_fmt, $defn);

         if (count($expressions) == 0) continue;
              
         // We have exprrssion to adda. We use a nested <dl> for the expressions.
         $exps = "  <dd class='expressions'>\n   <dl>\n"; 
         
         foreach ($expressions as $expression) 

                $exps .= sprintf($exp_fmt, $expression['source'], $expression['target']);

         $exps .= "  </dl>\n  </dd>\n";
         
         $dds .=  $exps ;              
      }

      return $dds;
   }
 
   // to do: make sure the array has both 1.) sample and 2.) its translation.
   public function add_samples_section(string $word, SentencesIterator $iter, TranslateInterface $trans) : string 
   {
      static $sec_samples = "<section class='samples'>";

      $str = $sec_samples;

      //if (count($iter) === 0)
      if (!$iter->valid())

          $str .= "<p><span class'bold'>" . trim($word) . "<span> has no sample sentsences.</p>";

      else 

          foreach ($iter as $s) {
          
             $translation = $trans->translate($s, 'en', 'de');
             
             $str .= "<p>" . $s . "</p><p>" . $translation . "</p>\n";
          }

      $str .= "</section>\n";

      $str = $this->tidy($str);

      return $str;
   }

   private function tidy(string $out) : string
   { 
     static $tidy_config = array(
                     'clean' => true, 
                     'output-xhtml' => true, 
                     'show-body-only' => true,
                     'wrap' => 0,
                     'indent' => true
                     ); 
                     
      $tidy = tidy_parse_string($out, $tidy_config, 'UTF8');

      $tidy->cleanRepair();

      return (string) $tidy;  
   }
}
