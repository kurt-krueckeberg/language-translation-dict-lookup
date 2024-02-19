<?php
declare(strict_types=1);
namespace Vocab;

class ExpressionsTable { 

   private static $sql = "insert into exprs(source, target, defn_id) values(:source, :target, :defn_id)";
   private static string $source = '';
   private static string $target = '';
   private static int $defn_id = -1;

   private \PDOStatement $insert_stmt;

   private \PDO $pdo;

   public function __construct(\PDO $pdo)
   {
      $this->pdo = $pdo;

      $this->insert_stmt = $pdo->prepare(self::$sql);

      $this->insert_stmt->bindParam(":source", self::$source, \PDO::PARAM_STR);

      $this->insert_stmt->bindParam(":target", self::$target, \PDO::PARAM_STR);

      $this->insert_stmt->bindParam(":defn_id", self::$defn_id, \PDO::PARAM_INT);	
   }

   /*
       TODO: This method shuld be redone to accept an interface not an array because... 
       ...this code will only works for Systran dictionary results.
    */
   public function insert(array $expression, int $defn_id) : int 
   {
      self::$source = $expression['source'];
      
      self::$target = $expression['target'];

      self::$defn_id = $defn_id;

      $rc = $this->insert_stmt->execute(); 

      return (int) $this->pdo->lastInsertId();
   }
}
