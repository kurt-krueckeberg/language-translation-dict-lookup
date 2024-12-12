<?php
declare(strict_types=1);
namespace Vocab;

/*
 * For each word, we need the part of speech (POS); for nouns, the gender and plural; 
 * for verbs, its conjucation; 
 * File Format: new-word1:pos: defn1, defn2, defn3
 * use a yml file?
 */

class TextDictionary implements DictionaryInterface {

   private \SplFileObject $errorLog;

   private array $defns; 

   public function __construct(\SplFileObject $file)
   {
      foreach ($file as $line) {
         
          $len = strchr($line, ':');

          $word = substr($line, 0, $len);
  
          $defns = explode(',', substr($line, $len + 1));

          $this->defns[$word] = $defns;
       }
   }

   final public function getDictionaryLanguages() : array
   {
      return array("de");
   } 

   private function logError(string $err) : void
   {
      if (!isset($this->errorLog)) $this->errorLog = new \SplFileObject("error.log", "w");

      $this->errorLog->fwrite($err);
   }
  
   final public function lookup(string $word, string $src, string $dest) : \Iterator 
   {      
      $array = $this->defns[$word];

      return TextDictionary::LookupResultsGenerator($array);
    }
     
    public static function LookupResultsGenerator(array $arr) : \Iterator
    {
        foreach ($arr as $key => $current) {
           
           yield match($current['source']['pos']) {

             'noun' => new SystranNoun($current),
             'verb' => new SystranVerb($current, function() use($current) : string { 
                    return $current['source']['inflection'];}
               ),
             default => new SystranWord($current)
           };
        }
    }
}
