<?php
declare(strict_types=1);
namespace Vocab;

/*
 * Implements: word_define() : string, pos() : Pos, and IteratorAggregate, which
 * returns [key,value] pair of [defintion, its associated expressions, if any]
 */
readonly class Word implements WordInterface, VisitorInterface {

   public array $match;

   function __construct(array $match)
   { 
      $this->match = $match;
   }

   static function SystranDefnsGenerator(array $match) : \Iterator
   {
     $targets = $match['targets'];
     
     foreach($targets as $key => $target) {
   
         yield $target['lemma'] => $target['expressions'];
     }
   }

   function get_pos() : Pos
   {
       return Pos::fromString($this->match['source']['pos']);
   } 

   function word_defined() : string
   {
     $tmp = $this->match['source']['lemma'];

     // Remove and any characters not in 'A-ZÖÄÜa-zaäöüß'.
     return preg_replace('/[^A-ZÖÄÜa-zaäöüß ]/', '', $tmp); 
   }

   function getIterator() : \Iterator
   {
      return self::SystranDefnsGenerator($this->match);        
   }

   function accept(InserterInterface $inserter) : int
   {
       return $inserter->insert_word($this);
   }
}
