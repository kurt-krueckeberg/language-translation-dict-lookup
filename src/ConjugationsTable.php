<?php
declare(strict_types=1);
namespace Vocab;

class ConjugationsTable { 

   private static $sql = "insert into conjs(conjugation) values(:conjugation)";
   private string $conjugation = '';

   private \PDOStatement $insert_stmt;

   private \PDO $pdo;

   public function __construct(\PDO $pdo)
   {
      $this->pdo = $pdo;

      $this->insert_stmt = $pdo->prepare(self::$sql);

      $this->insert_stmt->bindParam(":conjugation", $this->conjugation, \PDO::PARAM_STR);
   }

   public function insert(WordInterface $wrface) : int 
   {
      $this->conjugation = $wrface->conjugation();
      
      $this->insert_stmt->execute(); 

      return (int) $this->pdo->lastInsertId();
   }
}
