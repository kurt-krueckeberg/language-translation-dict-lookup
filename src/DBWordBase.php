<?php
declare(strict_types=1);
namespace Vocab;

abstract class DBWordBase {  

   static private \PDOStatement $stmts = [];

   static private $word_id; // <---- ????
   
   /*
     Uses PHP variable variables act as keys into $stmts array.
    */
   protected function get_stmt(string $sql_str) : \PDOStatement
   {
      if (!isset(self::$stmts[$$sql_str])) {
         
         $pdo_stmt = $this->pdo->prepare($sql_str);

         $this->->do_bind($$sql_str, self::$stmt);

         self::$stmts[$$sql_str] = $pdo_stmt;
      }

      return self::$stmts[$$sql_str];
   }

   abstract function do_bind(string $stmt_key, \PDOStatement) : void;
    
   public function __construct(\PDO $pdo, int $word_id)
   {
   }
}
