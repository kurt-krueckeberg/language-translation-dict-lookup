<?php
declare(strict_types=1);
namespace Vocab;

class FetchWord  {  
   
   private \PDOStatement $select_word; 
   private \PDOStatement $select_noun; 
   private \PDOStatement $select_verb; 

   private static $sql_wordselect = "select id, pos from words as w where w.word=:word";

   private static $sql_nounselect = "select w.id, w.pos, n.gender, n.plural from 
                                          words as w
                                     join
                                          nouns_data as n on w.id=nouns.word_id
                                     join
                                          defns on defns.word_id=w.id
                                     left join
                                          exprs on exprs.defn_id=defns.id 
                                     where w.id=:id";

   private static $sql_verbselect = "select w.id as word_id, w.pos, tenses.conjugation, defns.id, defns.defn, exprs.id as exprs_id, exprs.expr, translated_expr from
                                          words as w
                                                                          join 
                                         conjugated_verbs as v  on w.id=v.word_id
                                     join
                                         conjugated_tenses as tenses on tenses.id=v.conj_id
                                     join 
                                          defns on defns.word_id=w.id
                                     left join
                                          exprs on exprs.defn_id=defns.id 
                                     where w.id=:id"; 
                                           
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
