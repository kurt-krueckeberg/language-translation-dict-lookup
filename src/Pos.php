<?php
declare(strict_types=1);
namespace Vocab;

interface PosInterface {

  // function getInserterClass() : string;
   function getString() : string;
}

/*
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
   
// Q: Isn't this the same as using: $pos->name ??
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
*/

enum Pos  { 
 
   case noun;
   case verb;
   case adj;
   case adv;
   case conj;
   case other;

   public static function fromString(string $pos) : static
   {
        return match($pos) {
            'noun' =>   static::noun,
            'verb' =>   static::verb,
            'adj'  =>   static::adj,
            'adv'  =>   static::adv,
            'conj'  =>  static::conj,
            'other' =>  static::other,
            default =>  static::other
          };
   }
}
