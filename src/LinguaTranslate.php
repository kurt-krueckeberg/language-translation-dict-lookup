<?php
declare(strict_type=1);
namespace Vocab;

class LinguaTranslate extends RestApi implements TranslateInterface, DictionaryInterface {

   private \SplFileObject $errorLog;

   private \Collator $collator;
   
   public function __construct(Config $c)
   {
      parent::__construct($c, ProviderID::Lingua); 

      $this->collator = $c->getCollator(); 
   }

    final public function translate(string $text, string $to, string $from="") : string 
    {
       static $trans = array('method' => "GET");

       $query = array();
       
       $query['langpair'] = strtolower($from) . '-'  strtolower($to);;
       
       $query['query'] = $text;
       
       $contents = $this->request($trans['method'], '', ['query' => $query]); 

       $std = json_decode($contents);
       
       return $std->???????;       
   }
    
    final public function lookup(string $word, string $src, string $dest, bool $exact_match=false) : \Iterator 
    {

    }
}
