<?php
declare(strict_types=1);
namespace Vocab;

/*
  Note the use of the pluarl: RelatedVerbs not RelatedVerb
 */
readonly class VerbFamilyResult extends SystranVerb implements VerbInterface, ItertorAggregate {

   private array $related_verbs;
   private int $main_verb_index;

   function __construct(array $matches, int $main_index)
   { 
      parent::__construct($matches[$main_index], function() use($matches) : string {

        $conjugation = $matches[$this->main_verb_index]['source']['inflection'];

        // Change '(kommen/kam/gekommen)' to 'kommen,kam,gekommen' or
        return str_replace(array("(", "/", ")"), array("", ", ", ""), $conjugation); }
      ); 
   }

   public function getIterator() : \Iterator 
   {
      return new RelatedVerbsIterator($this->matches, $this->main_verb_index); // <-- This does not exitst~
   }

   function accept(InserterInterface $inserter) : int
   {
       return $inserter->insert_related_verbs($this);
   }
}
