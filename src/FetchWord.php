<?php
declare(strict_types=1);
namespace Vocab;

class FetchWord  {  
   
   private \PDOStatement $select_word; 
   private \PDOStatement $select_noun; 
   private \PDOStatement $select_verb; 

   private static $sql_wordselect = "select id, pos from words as w where w.word=:word";

  // TODO: Add definitions and expressions to these two select statements.
   private static $sql_nounselect = "select w.id, w.pos, n.gender, n.plural from 
                                          words as w
                                     join
                                          nouns_data as n
                                     on words.:id=nouns.word_id";

   private static $sql_verbselect = "select w.id, w.pos, v.conjugation from
                                          words as w
                                     join 
                                         conjugated_verbs as v
                                     on  w.:id=v.word_id
                                     join
                                         conjugated_tenses as tenses
                                     on  tenses.id=v.conj_id"; 
                                           
   private string $word = '';
   
   private \PDO $pdo;
   
   private POS $pos;
   
   private int $id = -1 ;

   public function __construct(\PDO $pdo)
   {
      $this->pdo = $pdo;

      $this->select_word = $pdo->prepare(self::$sql_wordselect);
      
      $this->select_word->bindParam(':word', $this->word, \PDO::PARAM_STR);     

      $this->select_noun = $pdo->prepare(self::$sql_nounselect);
              
      $this->select_noun->bindParam(':id', $this->id, \PDO::PARAM_INT);
              
      $this->select_verb = $pdo->prepare(self::$sql_verbselect);
              
      $this->select_verb->bindParam(':id', $this->id, \PDO::PARAM_INT);
   }

   function __invoke(string $word) : WordInterface | false
   {
      $this->word = $word;
      
      $rc = $this->select_word->execute();
      
      if ($rc == false)
          return false;
      
      $word = $this->select_word->fetch(\PDO::FETCH_ASSOC);
      
      $this->pos = POS::fromString($word['pos']);
      
      switch ($word['pos']) {
          
          case 'noun':
              $this->id = $word['id'];
              $result = $this->select_noun->execute();
              
              //TODO: Create NounInterface result
              break;
          
          case 'verb':
              
              $this->id = $word['id'];
              $result = $this->select_verb->execute();
              
              //TODO: Create VerbInterface result
              
              break;
          
          default:
              break;
      }
      
      
   }
}
