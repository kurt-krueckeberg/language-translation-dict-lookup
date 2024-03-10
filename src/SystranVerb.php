<?php
declare(strict_types=1);
namespace Vocab;

class SystranVerb extends SystranWord implements VerbInterface {

   private \Closure $conjugator;
   
   function __construct(array $match, callable $conjugator)
   { 
      parent::__construct($match);
      
      $this->conjugator = $conjugator;
   }

   public function conjugation() : string
   {
       $temp = ($this->conjugator)();       
       /* 
          Convert conjugations of the form '(arbeit/arbeite/gearbeitet)' to
          'arbeit,arbeite,gearbeitet' 
        */   
        $temp = trim($temp, "()");
        return str_replace("/", ",", $temp); 
   }

   function accept(InserterInterface $inserter) : int
   {
       return $inserter->insert_verb($this);
   }
}
