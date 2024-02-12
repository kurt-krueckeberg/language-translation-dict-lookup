<?php
declare(strict_types=1);
namespace Vocab;

readonly class SystranVerbFamilyResult extends SystranVerbResult implements VerbInterface, \IteratorAggregate {

   private int $main_verb_index;
   private array $matches;

   function __construct(array $matches, int $main_verb_index)
   { 
      parent::__construct($matches[$main_verb_index], function() : string {

      $conjugater = $this->matches[$this->main_verb_index]['source']['inflection'];
          
         return str_replace(array("(", "/", ")"), array( "", ", ", ""), $conjugater); 
         });

      $this->main_verb_index = $main_verb_index;

      $this->matches = $matches;
   }

  /*
    Returns conjugation of main verb whose conjugation the prefix/reflexsive verb shares.
    This method is not of use when inserting prefix verbs information into the database.
   public function conjugation() : string 
   {
      return $this->matches[$this->main_verb_index]['source']['inflection'];
   }
   */

   public function getIterator() : \Iterator 
   {
      return new RelatedVerbsIterator($this->matches, $this->main_verb_index);
   }

   function accept(InserterInterface $inserter) : int
   {
       return $inserter->insert_related_verbs($this);
   }
}
