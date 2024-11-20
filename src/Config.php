<?php
declare(strict_types=1);
namespace Vocab;

class Config {

   private static string $yml_config = "lang_translation_config.yml";
 
   public readonly array $settings;

   public function __construct() 
   {
      $this->settings = \yaml_parse_file(__DIR__ . '/' . self::$yml_config);
   }
   
 /*
   public function get_locale() : string
   {
     return $this->settings['language']['locale'];
   }

   public function getCollator() : \Collator
   {
     return new \Collator($this->get_locale()); 
   }

   function lookup_file() : string
   {
     return $this->settings['lookup_file'];
   }
*/
   function fetch_words() : array
   {
     $words = [];

     $file = new FileReader($this->settings['lookup_file'], "r");
    
      foreach ($file as $word) {
          
         $words[] = $word;
      }
     
      return $words;
   }
 
   function sentence_count() : int
   {
      return $this->settings['samples_count'];
   }
}
