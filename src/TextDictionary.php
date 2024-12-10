<?php
declare(strict_types=1);
namespace Vocab;

class TextDictionary implements DictionaryInterface {

   private \SplFileObject $errorLog;

   public function __construct()
   {
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
      // TODO: Finish     
      return TextDictionary::LookupResultsGenerator($matches);
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
