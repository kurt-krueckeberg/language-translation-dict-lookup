<?php
declare(strict_types=1);
namespace Vocab;

class DBVerb implements VerbInterface  {  
   
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

   function definitions() : \Iterator
   {
      // When the defn_id changes, we have a new definition, but with each separate definition,
      // we may have one or more expressions. We may have none. 
      //return array_column($this->rows, 'defn')?????;
   }
}
