<?php
declare(strict_types=1);
namespace Vocab;

class DBNoun extends DBWordBase implements NounInterface {  

   private callable $plural;

   private gender $gender;
   
   private string $word_defined;
   
   public function __construct(\PDO $pdo, string $word, int $word_id, callable $plural, callable $gender)
   {
      parent::__construct($pdo, $word_id);
      
      $this->word_defined = $word;
      $this->plural = $plural;
      $this->gender;
   }
   
   function word_defined(): string 
   {
      return $this->word_defined;
   }
   
   function plural() : string
   {
     return ($this->plural)();
   }

   function gender() : Gender
   {
     return ($this->gender)(); 
   } 
}
