<?php
declare(strict_types=1);
namespace Vocab;

// TODO: Get rid of AbstractDefinitionsIterator and put its iteation-related code in thie class.

abstract class DBWordBase extends AbstractDefinitionsIterator {  

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
 
   abstract protected function get_current(array $target) : DefinitionInterface | false;

   abstract function definitions() : AbstractDefinitionsIterator;
   
   function __construct(\PDO $pdo)
   {
      $this->pdo = $pdo;
   }
}
