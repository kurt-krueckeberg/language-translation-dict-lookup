<?php
declare(strict_types=1);
namespace Vocab;

readonly class SystranVerbResult extends SystranWordResult implements VerbInterface {

   private \Closure $conjugator;
   
   function __construct(array $match, callable $conjugator)
   { 
      parent::__construct($match);
      
      $this->conjugator = $conjugator;
   }

   public function conjugation() : string
   {
       return ($this->conjugator)();       
   }

   function accept(InserterInterface $inserter) : int
   {
       return $inserter->insert_verb($this);
   }
}
