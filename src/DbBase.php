<?php
declare(strict_types=1);
namespace Vocab;

class DbBase implements WordExistsInterface { 

   private static $word_exists_sql = "select 1 from words where word=:new_word";
   
   private \PDO $pdo;
   
   private static string $new_word = '';
   
   private static array $tables;// static array used by get_table(...) : mixed 

   private static $stmts; // static array used by get_stmt_trait::get_stmt(...) : \PDOStatement

   use get_stmt_trait;

   public function __construct(\PDO $pdo)
   {
      $this->pdo = $pdo;
   }

   function bind(\PDOStatement $pdostmt) : void
   {
      $pdostmt->bindParam(':new_word', self::$new_word, \PDO::PARAM_STR); 
   }

   public function word_exists(string $word) : bool
   {
      $word_exists_stmt = $this->get_stmt($this->pdo, 'word_exists_sql');

      self::$new_word = $word;

      $rc = $word_exists_stmt->execute();

     if ($rc === false) 
         
         throw new \ErrorException('SQL statement to test if word exist failed.');
     
     $rc = $word_exists_stmt->fetch(\PDO::FETCH_NUM);
         
     return ($rc === false) ? false : true;            
   }
      
   protected function get_table(string $table_name, string $namespace="Vocab") : mixed
   {
      $className = "$namespace\\$table_name";
  
      if (isset(self::$tables[$className]) === false) {

         $instance = new $className($this->pdo);

         self::$tables[$className] = $instance;
     } 

      return self::$tables[$className];
   }
}
