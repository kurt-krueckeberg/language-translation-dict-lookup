<?php
declare(strict_types=1);
namespace Vocab;

class DBWord extends DBWordBase implements WordInterface {  
  
   private array $row; 
  
   public function __construct(\PDO $pdo, array $row) 
   {
      parent::__construct($pdo, $row['word_id']);

      $this->row = $row;
   }

   function get_pos() : Pos
   {
      return Pos::from($this->row['pos']); 
   }

   function word_defined() : string
   {
      return $this->row['word']; 
   }
}
