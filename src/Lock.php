<?php
declare(strict_types=1);
namespace Vocab;

class Lock { 

   private \PDO $pdo;

   public function __construct(\PDO $pdo)
   {
     $this->pdo = $pdo; 
     $this->pdo->beginTransaction();  
     echo "beginTransaction() called\n";
   }
 
   public function __destruct()
   {
      $this->pdo->commit();
     echo "commit() called\n";
   }
}
