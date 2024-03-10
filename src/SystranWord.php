<?php
declare(strict_types=1);
namespace Vocab;

readonly class SystranWord implements WordInterface, VisitorInterface {

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

     // strip out any tilde or anything that is not a-zaööü. Keep ' ' for  reflexive verbs like
     // 'sich vorstellen'
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
