<?php
declare(strict_types=1);
namespace Vocab;

class ConjugationsTable { 

   private static $sql = "insert into verb_conjs(conjugation, word_id) values(:conjugation, :word_id)";

   private string $conjugation = '';

   private int $word_id = -1;

   private \PDOStatement $insert_stmt;

   private \PDO $pdo;

   public function __construct(\PDO $pdo)
   {
      $this->pdo = $pdo;

      $this->insert_stmt = $pdo->prepare(self::$sql);

      $this->insert_stmt->bindParam(":conjugation", $this->conjugation, \PDO::PARAM_STR);

      $this->insert_stmt->bindParam(":word_id", $this->word_id, \PDO::PARAM_INT);
   }

   public function insert(WordInterface $wrface, int $word_id) : int 
   {
      $this->conjugation = $wrface->conjugation();

      $this->word_id = $word_id;
      
      $this->insert_stmt->execute(); 

      return (int) $this->pdo->lastInsertId();
   }
}
