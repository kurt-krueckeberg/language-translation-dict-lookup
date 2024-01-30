<?php
declare(strict_types=1);
namespace Vocab;

enum Gender : string { 
   case Mas = 'm'; 
   case Fem = 'f'; 
   case Neu = 'n';
   case Unknown = 'u';
   
   public static function fromString(string $str) : static
   {
       return match($str) {
          'm' =>   static::Mas,
          'f' =>   static::Fem,
          'n'  =>  static::Neu,
          default => static::Unknown
       };
   }
}

