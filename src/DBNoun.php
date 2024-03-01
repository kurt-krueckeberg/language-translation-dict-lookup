<?php
declare(strict_types=1);
namespace Vocab;

class DBNoun extends DBWord implements NounInterface {  

   private array $row;
    
   public function __construct(\PDO $pdo, array $row) 
   {
      parent::__construct($pdo, $row['word_id']);
      
      $this->row = $row;
   }
   
   function plural() : string
   {
     return $this->row['plural'];
   }

   function gender() : Gender
   {
     return $this->row['gender'];
   } 
}
