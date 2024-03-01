<?php
declare(strict_types=1);
namespace Vocab;

class DBNoun extends DBWordBase implements NounInterface {  

   private array $row;
    
   public function __construct(\PDO $pdo, array $row) 
   {
      parent::__construct($pdo, $row['word_id']);
      
      $this->row = $row;
   }
 /*
   public function __construct(\PDO $pdo, string $word, int $word_id, callable $plural, callable $gender)
   {
      parent::__construct($pdo, $word_id);
      
      $this->word_defined = $word;
      $this->plural = $plural;
      $this->gender;
   }
  */
   function word_defined(): string 
   {
      return $this->row['defined'];
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
