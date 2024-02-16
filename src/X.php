<?php
declare(strict_types=1);
namespace Vocab;
use Vocab\AbstractDefinitionsIterator;

readonly class X implements WordInterface {

   public Pos $pos;

   function __construct(public string $word, string $pos)
   { 
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
}
