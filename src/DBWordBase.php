<?php
declare(strict_types=1);
namespace Vocab;

abstract class DBWordBase {  

   static private $stmts = [];

   static private $word_id; // <---- ????
       
   /*
     Uses PHP variable variables act as keys into $stmts array.
    */
   protected function get_stmt(\PDO $pdo, string $sql_str) : \PDOStatement
   {     
      if (!isset(self::$stmts[$sql_str])) {
         
         $pdo_stmt = $pdo->prepare($sql_str);

         $this->do_bind($sql_str, $pdo_stmt);

         self::$stmts[$sql_str] = $pdo_stmt;
      }

      return self::$stmts[$sql_str];
   }
        
   abstract protected function do_bind(string $stmt_key, \PDOStatement $stmt) : void;
   
   public function __construct(\PDO $pdo, int $word_id)
   {
   }
}
