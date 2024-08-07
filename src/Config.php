<?php
declare(strict_types=1);
namespace Vocab;

class Config {

   private static string $yml_config = "lang_translation_config.yml";

   public readonly string $namespace;
   
   public readonly array $config;

   public function __construct() 
   {
      $this->config = \yaml_parse_file(__DIR__ . '/' . self::$yml_config);

      $this->namespace = "Vocab";
   }
   
 /*
   public function get_locale() : string
   {
     return $this->config['language']['locale'];
   }

   public function getCollator() : \Collator
   {
     return new \Collator($this->get_locale()); 
   }

   function lookup_file() : string
   {
     return $this->config['lookup_file'];
   }
*/
   function fetch_words() : array
   {
     $words = [];

     $file = new FileReader($this->lookup_file(), "r");
    
      foreach ($file as $word) {
          
         $words[] = $word;
      }
     
      return $words;
   }
 
   function sentence_count() : int
   {
      return $this->config['samples_count'];
   }
}
