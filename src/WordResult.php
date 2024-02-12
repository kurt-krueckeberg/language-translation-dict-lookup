<?php
declare(strict_types=1);
namespace Vocab;
use Vocab\AbstractDefinitionsIterator;

abstract readonly class WordResult implements WordInterface, VisitorInterface {

   public string $word;
   public Pos $pos;

   function __construct(string $word, string $pos)
   { 
      $this->word = $match;
      
      $this->pos = Pos::fromString($pos);
   }
 
   function get_pos() : Pos
   {
       return $this->pos;
   } 

   function word_defined() : string
   {
     $tmp = $this->word;

     // strip out any tilde or anything that is not a-zaööü. Keep ' ' for  reflexive verbs like
     // 'sich vorstellen'
     return preg_replace('/[^A-ZÖÄÜa-zaäöü ]/', '', $tmp); 
   }

   function definitions() : AbstractDefinitionsIterator
   {
      return $this->get_definitions();      
   }

   abstract protected function get_definitions() : AbstractDefinitionsIterator;

   function accept(InserterInterface $inserter) : int
   {
       return $inserter->insert_word($this);
   }
}
