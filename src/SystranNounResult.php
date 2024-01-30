<?php
declare(strict_types=1);
namespace Vocab;

readonly class SystranNounResult extends SystranWordResult implements NounResultInterface  {
   private Gender $gender;
 
   private string $plural;
      
   public function __construct(array $match)
   {
      parent::__construct($match);     
 
      // Strip of the beginning "(pl:" and the ending ")"
      $this->plural = substr($this->match['source']['inflection'], strpos($this->match['source']['inflection'], ':') + 1, -1); 
 
      $this->gender = Gender::fromString($this->match['source']['info']);
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
 
