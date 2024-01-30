<?php
declare(strict_types=1);
namespace Vocab;

interface PosInterface {

  // function getInserterClass() : string;
   function getString() : string;
}

enum Pos implements PosInterface { 
 
   case Noun;
   case Verb;
   case Adj;
   case Adv;
   case Conj;
   case Other;

   public static function fromString(string $pos) : static
   {
        return match($pos) {
            'noun' =>   static::Noun,
            'verb' =>   static::Verb,
            'adj'  =>   static::Adj,
            'adv'  =>   static::Adv,
            'conj'  =>  static::Conj,
            'other' =>  static::Other,
            default =>  static::Other
          };
   }

   public function getString() : string
   {
       return match($this) {
           Pos::Noun  => 'noun',
           Pos::Verb  => 'verb',
           Pos::Adj => 'adj',
           Pos::Adv => 'adv',
           Pos::Conj => 'conj',
           Pos::Other => 'other'
       }; 
   }
}
