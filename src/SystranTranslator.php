<?php
declare(strict_types=1);
namespace Vocab;

class SystranTranslator extends RestApi implements TranslateInterface, DictionaryInterface {

   private \SplFileObject $errorLog;

   private \Collator $collator;
   
   public function __construct(array $config)
   {
      parent::__construct($config, 'systran'); 

      $this->collator = new \Collator($config['language']['locale']); 
   }

   public function getTranslationLanguages() : array
   {
      static $trans_languages = array('method' => "GET", 'route' => "translation/supportedLanguages");

      $contents = $this->request($trans_languages['method'], $trans_languages['route']);
             
      return json_decode($contents, true);
   } 

   final public function getDictionaryLanguages() : array
   {
      static $dict_languages = array('method' => "GET", 'route' => "resources/dictionary/supportedLanguages");

      $contents = $this->request($dict_languages['method'], $dict_languages['route']);
             
      return json_decode($contents, true);    
   } 

   /*
    *  NOTE: Systran requires the language codes to be lowercase.
    *  If the language is not utf-8, the default, then you must speciy the encoding using the 'options' parameter.
    */
   final public function translate(string $text, string $to, string $from="") : string 
   {
       static $trans = array('method' => "POST", 'route' => "translation/text/translate");

       $query = array();
       
       if ($from !== '') 
           $query['source'] = strtolower($from);
       
       $query['target'] = strtolower($to);
       
       $query['input'] = $text;
       
       $contents = $this->request($trans['method'], $trans['route'], ['query' => $query]); 

       $std = json_decode($contents);
       
       return $std->outputs[0]->output;       
   }

   private function logError(string $err) : void
   {
      if (!isset($this->errorLog)) $this->errorLog = new \SplFileObject("error.log", "w");

      $this->errorLog->fwrite($err);
   }
  
   final public function lookup(string $word, string $src, string $dest) : \Iterator 
   {      
      static $lookup = array('method' => "POST", 'route' => "resources/dictionary/lookup");

      $query = array();
      
      if ($src !== '') 
          $query['source'] = strtolower($src);
      
      $query['target'] = strtolower($dest);
      
      $query['input'] = $word;

      $contents = $this->request($lookup['method'], $lookup['route'], ['query' => $query]);
       
      $r = json_decode($contents, true); // convert JSON string to \stdClass

      $matches = $r['outputs'][0]['output']['matches'];
  
      $matches = $this->merge_tilde_verbs($matches);           
     
      return SystranTranslator::LookupResultsGenerator($matches);
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

   /* 
    * Merge the definitions for verbs returned in $matches[$i], for those $i, where
    * $matches[$i]['source'['lemma'] contains a tilde, as, for example, an~kommen
    * We remove the version 'an~kommen' and merge it with 'ankommen'.
    *
    * NOTE: Not all prefix verb version have a non-tilde version
    */
    private function merge_tilde_verbs($matches) : array
    {
       $results = [];
       
       for ($i = 0; $i < count($matches); ++$i) { 

           // Look for tilde-separated verbs.
           $pos = strpos($matches[$i]['source']['lemma'], "~");

           // If none found, save the prefix verb in results[]. 
           if ($pos === false || count($matches) == $i + 1) { 
               
               $results[] = $matches[$i];
               continue;
           }
          
           // Check if the next verb is a non-tild version?
           $j = $i + 1;   
           
           // Is the next verb a non-tilde version
           $cmp = strcmp(substr($matches[$i]['source']['lemma'], 0, $pos), substr($matches[$j]['source']['lemma'], 0, $pos));
                   
           if (0 != $cmp) {
               
               $results[] = $matches[$i];

           } else {

              // Create a new array with the merged definitions that adds any tilde-verb defintions not in the non-tilde version.
              $combined_definitions = $this->combine_definitions($matches[$j]['targets'], $matches[$i]['targets']);
              
              // Overwrite the non-tilde array of definitions (in ['targets']
              $matches[$j]['targets'] = $combined_definitions;
              
              // Save the non-tilde verb now with the merged definitions in results[]
              $results[] = $matches[$j]; 
              
              // Adjust $i, so we skip the non-tilde version
              $i = $j;
           }           
       }       

       return $results;
    }

   /*
    * Adds any definitions in $tilde['targets'] to the $nontilde['targets'] that are not present.
    */   
    private function combine_definitions(array $defns_nontilde, array $defns_tilde) : array
    {
       $cmp = function(array $left, array $right) : int { return strcmp($left['lemma'], $right['lemma']); };         
 
       usort($defns_tilde, $cmp); // sort by 'lemma' value
 
       usort($defns_nontilde, $cmp); // sort by 'lemma' value
 
       $bs = new binary_search();
 
       $additional_defns = [];
 
       for ($i = 0; $i < count($defns_tilde); ++$i) {
 
           $rc = $bs($defns_nontilde, $defns_tilde[$i]['lemma'], function(array $target, string $key) : int {
               
                 $rc = strcmp($target['lemma'], $key);
                 
                 return $rc;
                 });
 
           if ($rc === -1)
              $additional_defns[] = $defns_tilde[$i];
       }
       
       // Append the targets -- the definitions -- in the tilde array not found in the non-tilde array
       $all_definitions = array_merge($defns_nontilde, $additional_defns);
       
       return $all_definitions; 
   }
}
