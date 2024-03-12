<?php
declare(strict_types=1);
namespace Vocab;

class FetchConjugation  {  
   
   private static $sql_select =  "select vc.conj_id from words as w inner join verbs_conjs as vc on w.id=vc.word_id where w.word=:word";

   private  static $stmts;
                                           
   private static string $word = '';

   private $stmt; 
   
   private \PDO $pdo;
   
   use get_stmt_trait;

   public function __construct(\PDO $pdo)
   {
      $this->pdo = $pdo;
   }

   protected function bind(\PDOStatement $stmt) : void
   {
      $stmt->bindParam(':word', self::$word, \PDO::PARAM_STR);     
   }

   /*
      Returns: array with 'conj_id' and 'onjugation'.
    */
   function __invoke(string $word) : array | false  
   {
      $stmt = $this->get_stmt($this->pdo, 'sql_select');

      self::$word = $word;
      
      $rc = $stmt->execute();
      
      if ($rc == false) {
          
          return array(false, false);
      }
      
      $row = $stmt->fetch(\PDO::FETCH_ASSOC);

      if ($row === false) return false;
      
      return $row;
   }
}
