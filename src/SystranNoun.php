<?php
declare(strict_types=1);
namespace Vocab;

readonly class SystranNoun extends SystranWord implements NounInterface {
    
   private Gender $gender;
 
   private string $plural;
      
   public function __construct(array $match)
   {
      parent::__construct($match);     
 
      // Strip "(pl:" at the beginning and the ")" at the end.
      $this->plural = substr($match['source']['inflection'], strpos($match['source']['inflection'], ':') + 1, -1); 
 
      // Unlike try($in), which throws ValueErrer if $in does not match a case, tryFrom($in) will simply return null.
      $gender = Gender::tryFrom($match['source']['info']);

      $this->gender = (is_null(Gender::tryFrom($match['source']['info']))) ? Gender::Unknown : $gender;
   }
 
   public function gender() : Gender
   {
      return $this->gender; 
   } 
  
   public function plural() : string
   {
      return $this->plural;
   }
 
   function accept(InserterInterface $inserter) : int
   {
       return $inserter->insert_noun($this);
   } 
 }