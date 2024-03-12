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
      $this->plural = substr($this->match['source']['inflection'], strpos($this->match['source']['inflection'], ':') + 1, -1); 
 
      $this->gender = Gender::from($this->match['source']['info']);
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
