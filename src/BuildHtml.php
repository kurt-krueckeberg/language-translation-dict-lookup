<?php
declare(strict_types=1);

namespace Vocab;

use \SplFileObject as File; 

class BuildHtml {

     private File  $out;
     private bool $b_saved;       
     private string $src;       
     private string $dest;      
     private HtmlBuilder $html_builder;      

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

    public function __construct(string $html_filename, string $src, string $dest)
    { 
       $this->b_saved = false;
       
       $this->src = $src;
       
       $this->dest = $dest;

       $this->out = new File($html_filename . ".html", "w"); 

       $this->html_builder = new HtmlBuilder();

       $this->out->fwrite(self::$out_start);
   }
 
   public function add_definitions(WordInterface $wrface) : void
   {
      $section = $this->html_builder->build_definitions_section($wrface);

      $this->out->fwrite($section);
 
      return;
   }

   public function add_samples(string $word, \Traversable $iter, TranslateInterface $trans) : void 
   {
      $section = $this->html_builder->add_samples_section($word, $iter, $trans);

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
}
