<?php
declare(strict_types=1);
namespace Vocab;

class ConjugatedVerbsTable { 

   private static $sql = "insert into conjugated_verbs(conj_id, word_id) values(:conj_id, :word_id)";
   private int $conj_id = -1;
   private int $word_id = -1;

   private \PDOStatement $insert_stmt;
   private \PDO $pdo;

   public function __construct(\PDO $pdo)
   {
      $this->pdo = $pdo;

      $this->insert_stmt = $pdo->prepare(self::$sql);

      $this->insert_stmt->bindParam(":conj_id", $this->conj_id, \PDO::PARAM_INT);

      $this->insert_stmt->bindParam(":word_id", $this->word_id, \PDO::PARAM_INT);
   }

   public function insert(int $conj_id, int $word_id) : int 
   {
      $this->conj_id = $conj_id;
      $this->word_id = $word_id;
      $this->insert_stmt->execute(); 

      return (int) $this->pdo->lastInsertId();
   }
}
