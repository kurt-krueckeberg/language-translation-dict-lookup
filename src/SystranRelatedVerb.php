<?php
declare(strict_types=1);
namespace Vocab;

class SystranRelatedVerb extends SystranWord implements RelatedVerbInterface  {

   private string $main_verb;

   function __construct(array $match)
   { 
      parent::__construct($match);

      $this->main_verb = $match['source']['term'];
   }

   function main_verb() : string
   {
      return $this->main_verb; 
   }

   function accept(InserterInterface $inserter) : int
   {
       return $inserter->insert_related_verb($this);
   }
}
