<?php
declare(strict_types=1);
namespace Vocab;

class DefnsTable { 

   private static $sql = "insert into defns(defn, word_id) values(:defn, :word_id)";
   private string $defn = '';
   private int $word_id = -1;

   private \PDOStatement $insert_stmt;

   private \PDO $pdo;

   public function __construct(\PDO $pdo)
   {
      $this->pdo = $pdo;

      $this->insert_stmt = $pdo->prepare(self::$sql);

      $this->insert_stmt->bindParam(":defn", $this->defn, \PDO::PARAM_STR);

      $this->insert_stmt->bindParam(":word_id", $this->word_id, \PDO::PARAM_INT);	
   }

   public function insert(string $defn, int $word_id) : int 
   {
      $this->defn = $defn;
      $this->word_id = $word_id;
      $this->insert_stmt->execute(); 

      return (int) $this->pdo->lastInsertId();
   }
}
