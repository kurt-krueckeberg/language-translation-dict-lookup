<?php
declare(strict_types=1);
namespace Vocab;

abstract class DBWordBase {  

   static private $stmts = [];
    
   /*
     Uses PHP variable variables act as keys into $stmts array.
    */
   protected function get_stmt(\PDO $pdo, string $str) : \PDOStatement
   {     
      if (!isset(self::$stmts[$str])) {
                   
         $pdo_stmt = $this->prepare_and_bind($pdo, $str); // <---- BUG

         self::$stmts[$str] = $pdo_stmt;
      }

      return self::$stmts[$str];
   }
        
   abstract protected function prepare_and_bind(\PDO $pdo, string $str) : \PDOStatement;
   
}
