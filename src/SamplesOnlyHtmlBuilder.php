<?php
declare(strict_types=1);

namespace Vocab;

use \SplFileObject as File; 

class SamplesBuildHtml {

     private File  $out;
     private int $sample_count;
    
     private HtmlBuilder $html_builder;      
     private LeipzigSentenceFetcher $sample_fetcher;      

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

    public function __construct(Config $c, string $html_filename, int $sample_count = 3)
    { 
       $this->b_saved = false;
       
       $this->sample_count = $sample_count;
       
       $this->out = new File($html_filename . ".html", "w"); 

       $this->sample_fetcher = new LeipzigSentenceFetcher($c);

       $this->html_builder = new HtmlBuilder();
   }
   
   function build_output(\SplFileObject $file)
   {
      $this->out->fwrite(self::$out_start);
      
      foreach ($file as $word) {
          
        $iter = $this->sample_fetcher->fetch($word, $this->sample_count);  
    
        $this->add_samples($word, $iter);
      }
      
      $this->out->fwrite(self::$out_end);       
   }
   
   // to do: make sure the array has both 1.) sample and 2.) its translation.
   private function add_samples_section(string $word, \Traversable $iter) : string 
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
  
   private function tidy(string $input) : string
   { 
     static $tidy_config = array(
                     'clean' => true, 
                     'output-xhtml' => true, 
                     'show-body-only' => true,
                     'wrap' => 0,
                     'indent' => true
                     ); 
                     
      $tidy = tidy_parse_string($input, $tidy_config, 'UTF8');

      $tidy->cleanRepair();

      return (string) $tidy;  
   }
 

   private function add_samples(string $word, \Traversable $iter) : void 
   {
      $section = $this->add_samples_section($word, $iter);

      $this->out->fwrite($section);

      return;
  } 
}
