<?php
declare(strict_types=1);
namespace Vocab;

trait get_stmt_trait {

   protected function get_stmt(\PDO $pdo, string $str) : \PDOStatement
   {     
      if (!isset(self::$stmts[$str])) { // <-- define the static array $stmts in the class using the trait.

         $class_name = get_class($this); 
          
         $sql = $class_name::$$str; 
          
         $stmt = $pdo->prepare( $sql ); 

         $this->bind($stmt, $str);

         self::$stmts[$str] = $stmt;
      }
      
      return self::$stmts[$str];
   }

   abstract function bind(\PDOStatement $pdostatement, string $str) : void;
}
