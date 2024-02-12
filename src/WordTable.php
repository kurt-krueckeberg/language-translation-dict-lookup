<?php
declare(strict_types=1);
namespace Vocab;

class WordTable implements TableInsertInterface {  
   
   //private \PDO $pdo;
   private \PDOStatement $insert_stmt; 
   private static $sql_insert = "insert into words(word, pos) values(:word, :pos)";

   private string $word = '';
   private string $pos = '';

   private \PDO $pdo;

   public function __construct(\PDO $pdo)
   {
      $this->pdo = $pdo;

      $this->insert_stmt = $pdo->prepare(self::$sql_insert);
      
      $this->insert_stmt->bindParam(':word', $this->word, \PDO::PARAM_STR);
     
      $this->insert_stmt->bindParam(':pos', $this->pos, \PDO::PARAM_STR);     
   }

   public function insert(WordResultInterface $wrface, int $id=-1) : int
   {
      $this->word = $wrface->word_defined();
      
      $this->pos  = $wrface->get_pos()->getString();
      
      $rc = $this->insert_stmt->execute();

      /*
      Note: lastInsertId() does not return the primary key; instead it returns the name of
      the sequence object -- whatever that is -- from which the ID should be returned.
      
      Someone added this comment concerning MySQL:
      
      "With no argument, LAST_INSERT_ID() returns a BIGINT UNSIGNED (64-bit) value representing the first automatically generated value
      successfully inserted for an AUTO_INCREMENT column as a result of the most recently executed INSERT statement."
       */
      
      $id = (int) $this->pdo->lastInsertId();

      return $id;
   }
}
