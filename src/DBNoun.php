<?php
declare(strict_types=1);
namespace Vocab;

class DBNoun extends DBWordBase implements NounInterface {  

   private Pos $pos;
   
   private string $word_defined;
   
   public function __construct(\PDO $pdo, Pos $pos, string $word, int $word_id)
   {
      parent::__construct($pdo, $word_id);
      
      $this->word_defined = $word;
      
      $this->pos = $pos;
   }
   
   public function get_pos() : Pos
   {
       return $this->pos;
   }
   
   function word_defined(): string 
   {
      return $this->word_defined;
   }
   
   function plural() : string
   {
     return $this->rows[0]['plural'];
   }

   function gender() : Gender
   {
     return Gender::fromString($this->rows[0]['gender']);
   } 
}
