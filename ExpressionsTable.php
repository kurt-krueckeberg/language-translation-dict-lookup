<?php
declare(strict_types=1);
namespace Vocab;

class ExpressionsTable { 

   private static $sql = "insert into exprs(expr, translated_expr, defn_id) values(:expr, :translated_expr, :defn_id)";
   private string $expr = '';
   private string $translated_expr = '';
   private int $defn_id = -1;

   private \PDOStatement $insert_stmt;

   private \PDO $pdo;

   public function __construct(\PDO $pdo)
   {
      $this->pdo = $pdo;

      $this->insert_stmt = $pdo->prepare(self::$sql);

      $this->insert_stmt->bindParam(":expr", $this->expr, \PDO::PARAM_STR);

      $this->insert_stmt->bindParam(":translated_expr", $this->translated_expr, \PDO::PARAM_STR);

      $this->insert_stmt->bindParam(":defn_id", $this->defn_id, \PDO::PARAM_INT);	
   }

   /*
       TODO: This method shuld be redone to accept an interface not an array because... 
       ...this code will only works for Systran dictionary results.
    */
   public function insert(array $expression, $foreign_key) : int 
   {
      $this->expr = $expression['source'];
      
      $this->translated_expr = $expression['target'];

      $this->defn_id = $foreign_key;
      $this->insert_stmt->execute(); 

      return (int) $this->pdo->lastInsertId();
   }
}
