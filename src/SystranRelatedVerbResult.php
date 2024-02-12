<?php
declare(strict_types=1);
namespace Vocab;

readonly class SystranRelatedVerbResult extends SystranWordResult implements RelatedVerbInterface  {

   private string $main_verb;

   function __construct(array $match)
   { 
      $this->main_verb = $match['source']['term'];
      parent::__construct($match);
   }

   function get_main_verb() : string
   {
      return $this->main_verb; 
   }

   function accept(InserterInterface $inserter) : int
   {
       return $inserter->insert_related_verb($this);
   }
}
