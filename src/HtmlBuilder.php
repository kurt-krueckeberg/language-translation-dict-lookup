<?php
declare(strict_types=1);
namespace Vocab;

class HtmlBuilder implements HtmlBuilderInterface {
    
     private File  $out;
     private bool $b_saved;       

static private string $out_start = <<<html_eos
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="de">
   <head>
      <title>German Vocab</title>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" type="text/css" media="screen" href="css/vocab.css"> 
      <link rel="stylesheet" type="text/css" media="print" href="css/print-vocab.css"> 
   </head>
<body>
html_eos;

static private string $out_end = <<<html_end
    </body>
</html>
html_end;

   public function __construct(string $html_filename)
   { 
      $this->b_saved = false;
      
      $this->out = new File($html_filename . ".html", "w"); 

      $this->out->fwrite(self::$out_start);
   }
 
   public function add_definitions(WordInterface $wrface) : void
   {
      $section = $this->build_definitions_section($wrface);

      $this->out->fwrite($section);
 
      return;
   }

   public function add_samples(string $word, \Traversable $iter) : void 
   {
      $section = $this->add_samples_section($word, $iter);

      $this->out->fwrite($section);

      return;
  } 

  public function __destruct()
  {
     $this->save();        
  } 

  public function save()
  {
     if (!$this->b_saved) {

          $this->out->fwrite(self::$out_end);
          $this->b_saved = true;
      } 
  }

   public function build_definitions_section(WordInterface $wrface) : string
   {
      static $sec_start =  "<section class='definitions'>\n";
      static $dl_start = "  <dl class='hwd'>\n";

      static $fmt = "  <dt>\n   <ul>\n    <li>%s</li>\n    <li class='pos'>%s</li>\n   </ul>\n  </dt>\n";    

      $sec = $sec_start;
 
      $dl = $dl_start;

      $dl .= sprintf($fmt, $wrface->word_defined(), strtoupper($wrface->get_pos()->value));

      $defns = $this->add_defn($wrface); 
          
      $dl .= $defns . " </dl>\n";

      $sec .= $dl;
             
      $sec .= "</section>\n";

      return $sec;   
   } 

   private function add_defn(\Traversable $defnsIter) : string
   {       
      $dds = '';
      static $defn_fmt =  "  <dd>%s</dd>\n";
      static $exp_fmt =  "    <dt>%s</dt>\n    <dd>%s</dd>\n";

      foreach ($defnsIter as $defn => $expressions) {

         $dds .= sprintf($defn_fmt, $defn);

         if (count($expressions) == 0) { continue; }
              
         // We have exprrssion to adda. We use a nested <dl> for the expressions.
         $exps = "  <dd class='expressions'>\n   <dl>\n"; 
         
         foreach ($expressions as $expression) {

                $exps .= sprintf($exp_fmt, $expression['source'], $expression['target']); 
         }
         
         $exps .= "  </dl>\n  </dd>\n";
         
         $dds .=  $exps ;              
      }

      return $dds;
   }
 
   public function add_samples_section(string $word, \Traversable $iter) : string 
   {
      static $sec_samples = "<section class='samples'>";

      $str = $sec_samples;

      if (!$iter->valid()) {

          $str .= "<p><span class'bold'>" . trim($word) . "<span> has no sample sentsences.</p>";

      } else {

          foreach ($iter as $s) {
                        
             $str .= "<p>" . $s['sample'] . "</p><p>" . $s['translation'] . "</p>\n";             
          }
      }
      
      $str .= "</section>\n";

      $result = $this->tidy($str);

      return $result;
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
                     
      $tidy = \tidy_parse_string($out, $tidy_config, 'utf8');

      $tidy->cleanRepair();

      return (string) $tidy;  
   }
}
