<?php
declare(strict_types=1);
namespace Vocab;

readonly class DBVerb implements VerbInterface {

   private \PDOStatement $stmt;

   private array $arr;
   
   function __construct(\PDOStatement $stmt)
   { 
      $this->stmt = $stmt;
      $this->arr = $this->stmt->fetchAll(\PDO::FETCH_ASSO);
   }

   public function conjugation() : string
   {
       return ($this->conjugator)();       
   }
}
