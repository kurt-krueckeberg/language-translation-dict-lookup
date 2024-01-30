<?php
declare(strict_types=1);
namespace Vocab;

class RelatedVerbsTable implements LinkTableInsertInterface  { 

   private static $sql = "insert into related_verbs(word_id, related_id) values(:word_id, :related_id)";
   private \PDOStatement $stmt;
   private int $word_id;
   private int $related_id;

   private \PDO $pdo;

   public function __construct(\PDO $pdo)
   {
      $this->pdo = $pdo;

      $this->stmt = $pdo->prepare(self::$sql);
   }

   public function insert(int $main_verb_id, int $related_id) : int
   {
       $this->word_id = $main_verb_id;
       $this->related_id = $related_id;
 
       $this->stmt->execute();

       return (int) $this->pdo->lastInsertId();
   }
}
