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
      
      if ($rc == false)
          return false;
      
      $row = $this->select_word->fetch(\PDO::FETCH_ASSOC);
      
      return array('pos' => Pos::fromString($row['pos']), 'word_id' => $row['word_id']);
      
      /*
      switch ($row['pos']) {
          
          case 'noun':

              if (true !== $result = $this->select_noun->execute())
                     throw new \LogicException("Could not find noun gender and plural for $word.\n") ;
              
              $result = $this->select_noun->fetch(\PDO::FETCH_ASSOC);
                    
              break;
          
          case 'verb':
      
              if (true !== $result = $this->select_verb->execute())
                     throw new \LogicException("Could not find verb conjuation for $word.\n") ;
              
              $result = $this->select_verb->fetch(\PDO::FETCH_ASSOC);
              
              $conj = $result['conjuation'];
                   
              break;
          
          default:
              break;
      }
      */
      
   }
}
