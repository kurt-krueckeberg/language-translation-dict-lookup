<?php
declare(strict_types=1);
namespace Vocab;

class DBVerb extends DBWord implements VerbInterface  {  
   
   private \PDOStatement $stmt;
   private array $rows;

   public function __construct(\PDOStatement $stmt)
   {
      $this->stmt = $stmt;
      $this->rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
   }

   function conjuation() : string
   {
      $this->rows[0]['conjugation'];
   }

   function pos() : string
   {
      $this->rows[0]['pos'];
   }

   // This is a base class method.
   function definitions() : \Iterator
   {
      return array_column($this->rows, 'defn')?????;
   }
}
