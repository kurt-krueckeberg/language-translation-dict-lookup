<?php
declare(strict_types=1);
namespace Vocab;

class FetchWord  {  
   
   private \PDOStatement $select_word; 
   
   private static $sql_wordselect = "select id as word_id, pos from words as w where w.word=:word";
                                           
   private string $word = '';
   
   private \PDO $pdo;
   
   readonly public POS $pos;
   
   private int $word_id = -1 ;

   public function __construct(\PDO $pdo)
   {
      $this->pdo = $pdo;

      $this->select_word = $pdo->prepare(self::$sql_wordselect);
      
      $this->select_word->bindParam(':word', $this->word, \PDO::PARAM_STR);     
   }

   function __invoke(string $word) : array | false
   {
      $this->word = $word;
      
      $rc = $this->select_word->execute();
      
      if ($rc == false) {
          
          return array(false, false);
      }
      
      $row = $this->select_word->fetch(\PDO::FETCH_ASSOC);
      
      return array(Pos::fromString($row['pos']), $row['word_id']);     
   }
}
