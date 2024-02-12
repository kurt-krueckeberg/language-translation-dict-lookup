<?php
declare(strict_types=1);
namespace Vocab;

abstract readonly class PrefixVerbResult extends WordResult implements VerbInterface {

   public VerbFamilyResult $main_verb;

   // private int $main_verb_index;
   private int $related_verb_index;

   function __construct(RelatedVerbsResult $main_verb, int $related_index)
   { 
      //??? parent::__construct($match);
      
      //??
   }

  /*
    Returns conjugation of main verb whose conjugation the prefix/reflexsive verb shares.
    This method is not of use when inserting prefix verbs information into the database.
   */
   public function conjugation() : string 
   {
      return $this->main_verb->conjugation();
   }

   function accept(InserterInterface $inserter) : int
   {
       return $inserter->insert_related_verb($this);
   }
}
