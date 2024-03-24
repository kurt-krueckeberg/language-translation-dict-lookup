<?php
declare(strict_types=1);
namespace Vocab;

class SystranTranslator extends RestApi implements TranslateInterface, DictionaryInterface {

   private \SplFileObject $errorLog;

   private \Collator $collator;
   
   public function __construct(Config $c)
   {
      parent::__construct($c, ProviderID::systran); 

      $this->collator = $c->getCollator(); 

      $this->createLookupIterator = new CreateSystranLookupResultsIterator;
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
   
   /*
      exact_match = true will not return prefix verb variants or noun forms of verbs, like Aussehen for aussehen.
    */

   final public function lookup(string $word, string $src, string $dest, bool $exact_match=false) : \Iterator 
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

      return $this->createLookupIterator($word, $matches, $this->collator);
    }
    
     /*
     * Examines $match->source
     * 
     * "source": {
            "inflection": "(pl:Frauen)",
            "info": "f",
            "lemma": "Frau",
            "phonetic": "",
            "pos": "noun",
            "term": "Frau"
        }
     * 
     * and returns array with:
     * 'word' => the word as it will b displayed, with plrual, if noun; with conjugation, if verb.
     * 'pos' => the part of speech.
     * 
     */
    
    private function get_source_info(\stdClass $match) : array
    {        
       if ($match->source->pos == 'noun') {
           /*         
           if ($match->source->info == 'm')
               $gender = 'der';
           else if ($match->source->info == 'n')
               $gender = 'das';
           else  
               $gender = 'die';
                      
           $word = $gender . ' ' . $match->source->lemma;
           
           if (strlen($match->source->inflection) !== 0) 
               
                $word .= ' ' . $match->source->inflection;

           else 
               $word .= " (no plural)";
            */
                       
                  
        } else if ($match->source->pos == "verb") 
            
            $word = $match->source->lemma . ' ' . $match->source->inflection;
        else 
            $word  = $match->source->lemma;   
                
        return array('word' => $word, 'pos' => $match->source->pos, 'gender' => $match->source->info);
   }   
}
