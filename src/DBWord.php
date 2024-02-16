<?php
declare(strict_types=1);
namespace Vocab;

class DBWord extends DBWordBase {  
  
   private static $sql_wordselect = "select id, pos from words as w where w.word=:word";
                                           
   private string $word = '';
   
   private \PDO $pdo;
   
   private POS $pos;
   
   private int $id = -1 ;

   public function __construct(\PDO $pdo, string $word)
   {
      parent::get_stmt($pdo, $word) 
      parent::__construct($pdo, $word_id);
            
   }

   function __invoke(string $word) : WordInterface | false
   {
      $this->word = $word;
      
      $rc = $this->select_word->execute();
      
      if ($rc == false)
          return false;
      
      $row = $this->select_word->fetch(\PDO::FETCH_ASSOC);
      
      $this->pos = POS::fromString($row['pos']);
      
      switch ($row['pos']) {
          
          case 'noun':
              $this->id = $row['id'];
              
              if (true !== $result = $this->select_noun->execute())
                     throw new \LogicException("Could not find noun gender and plural for $word.\n") ;
              
              $result = $this->select_noun->fetch(\PDO::FETCH_ASSOC);
              
              //TODO: Create NounInterface result
              break;
          
          case 'verb':
              
              $this->id = $row['id'];
              if (true !== $result = $this->select_verb->execute())
                     throw new \LogicException("Could not find verb conjuation for $word.\n") ;
              
              $result = $this->select_verb->fetch(\PDO::FETCH_ASSOC);
              
              $conj = $result['conjuation'];
              //TODO: Create VerbInterface result
              
              break;
          
          default:
              break;
      }
      
      
   }
}
