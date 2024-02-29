<?php
declare(strict_types=1);
namespace Vocab;

class DBVerb extends DBWordBase implements VerbInterface {
    
   private string $word_defined;

   function __construct(\PDO $pdo, array $row, string $word_defined, int $word_id) 
   {
      parent::__construct($pdo, $word_id);
      
      $this->word_defined = $word_defined;
   }
   
   public function get_pos() : Pos
   {
      return Pos::fromString($this->rows[$this->pos]);
   }

   function conjugation() : string
   {
     return $this->row['conjugation'];
   }

   function word_defined() : string
   {
     return $this->word_defined;
   } 
}