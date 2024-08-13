<?php
declare(strict_types=1);
namespace Vocab;

class FetchWord  {  
   
   private \PDOStatement $select_word; 
   
   private static $sql_wordselect = "select word, id as word_id, pos from words where word LIKE :word";
                                           
   private static string $word = '';
   
   private \PDO $pdo;
   
   readonly public POS $pos;
   
   private int $word_id = -1 ;

   public function __construct(\PDO $pdo)
   {
      $this->pdo = $pdo;
      
      self::$word = '%';

      $this->select_word = $pdo->prepare(self::$sql_wordselect);
      
      $this->select_word->bindParam(':word', self::$word, \PDO::PARAM_STR);     
   }

   function __invoke(string $input_word) : array | false
   {
      self::$word = "%$input_word%";

      $rc = $this->select_word->execute();
      
      if ($rc == false) {
          
          return false;
      }
      
      $row = $this->select_word->fetch(\PDO::FETCH_ASSOC);

      if ($row === false) 
          return false;
      
      return $row;
   }
}
