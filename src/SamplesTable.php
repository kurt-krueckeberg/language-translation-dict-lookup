<?php
declare(strict_types=1);
namespace Vocab;

class SamplesTable  {  
   
   //private \PDO $pdo;
   private \PDOStatement $insert_stmt; 
   private static $sql_insert = "insert into samples(sample, trans, word_id) values(:sample, :trans, :word_id)";

   private static string $sample = '';
   private static string $trans = '';
   private static int $word_id = -1;

   private \PDO $pdo;

   public function __construct(\PDO $pdo)
   {
      $this->pdo = $pdo;

      $this->insert_stmt = $pdo->prepare(self::$sql_insert);

      $this->insert_stmt->bindParam(':sample', self::$sample, \PDO::PARAM_STR);     

      $this->insert_stmt->bindParam(':trans', self::$trans, \PDO::PARAM_STR);     
      
      $this->insert_stmt->bindParam(':word_id', self::$word_id, \PDO::PARAM_INT);
   }

   public function insert(string $sample, string $trans, int $word_id) : int 
   {
      self::$sample = $sample;

      self::$trans = $trans; 
      
      self::$word_id  = $word_id;
      
      $rc = $this->insert_stmt->execute();
      
      if ($rc === false) {
             
          throw new \Exception("Samples for sord '$word' were not inserted successfully.");
      }
             

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
