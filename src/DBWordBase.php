<?php
declare(strict_types=1);
namespace Vocab;

abstract class DBWordBase {  

   static private $stmts = [];
    
   /*
     Uses PHP variable variables act as keys into $stmts array.
    */
/*
   protected function get_stmt(\PDO $pdo, string $str) : \PDOStatement
   {     
      if (!isset(self::$stmts[$str])) {
                   
         $pdo_stmt = $this->prepare_and_bind($pdo, $str); // <---- BUG

         self::$stmts[$str] = $pdo_stmt;
      }

      return self::$stmts[$str];
   }
*/

   private \PDO $pdo;

   protected function get_stmt(\PDO $pdo, string $str) : \PDOStatement
   {     
      if (!isset(self::$stmts[$str])) {
                   
         $stmt = $this->pdo->prepare( $this->get_sql($str) ); 

         $this->bind($pdo, $stmt);

         self::$stmts[$str] = $stmt;
      }

      return self::$stmts[$str];
   }
        
   abstract protected function bind(\PDOStatement $pdo, string $str) : void; 
   
   function __construct(\PDO $pdo)
   {
      $this->pdo = $pdo;
   }
}
