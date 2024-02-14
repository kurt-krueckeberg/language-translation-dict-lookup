<?php
declare(strict_types=1);
namespace Vocab;

abstract class DBWordBase {  

   static private $stmts = [];
    
   protected \PDO $pdo;

   protected function get_stmt(\PDO $pdo, string $str) : \PDOStatement
   {     
      if (!isset(self::$stmts[$str])) {
                   
         $stmt = $this->pdo->prepare( $this->get_sql($str) ); 

         $this->bind($stmt, $str);

         self::$stmts[$str] = $stmt;
      }

      return self::$stmts[$str];
   }
        
   abstract protected function bind(\PDOStatement $pdo, string $str) : void; 
   abstract protected function get_sql(string $str) : string; 
   abstract function definitions() : AbstractDefinitionsIterator;
   
   function __construct(\PDO $pdo)
   {
      $this->pdo = $pdo;
   }
}
