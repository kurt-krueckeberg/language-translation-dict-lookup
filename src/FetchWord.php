<?php
declare(strict_types=1);
namespace Vocab;

class FetchWord  {  
   
   private \PDOStatement $select_word; 
   private \PDOStatement $select_noun; 
   private \PDOStatement $select_verb; 

   private static $sql_wordselect = "select pos from words as w where w.word=:word";

   private static $sql_nounselect = "select words.pos, nouns.gender, nouns.plural from 
                                          words
                                     join
                                          nouns_data as nouns
                                     on words.:id=nouns.word_id";

   private static $sql_verbselect = "select w.pos, v.conjugation from
                                          words as w
                                     join 
                                         conjugated_verbs as v
                                     on  w.:id=v.word_id
                                     join
                                         conjugated_tenses as tenses
                                     on  tenses.id=v.conj_id"; // TODO: Add definitions and expressions to this.
                                           
   private string $word = '';
   
   private \PDO $pdo;
   
   private POS $pos;

   public function __construct(\PDO $pdo)
   {
      $this->pdo = $pdo;

      $this->select_word = $pdo->prepare(self::$sql_wordselect);
      
      $this->select_word->bindParam(':word', $this->word, \PDO::PARAM_STR);     

      $this
   }

   function find(string $word) : WordResultInterface | false
   {
      $this->word = $word;
      
      $rc = $this->word_select->execute();
      
      if ($rc == false)
          return false;
      
      $word = $this->word_select->fetch(\PDO::FETCH_ASSOC);
      
      $this->pos = POS::fromString($word['pos']);
      
      switch ($word['pos']) {
          
          'noun':
               
              break;
          
          'verb':
              // select verb inflection from verb 
              break;
          
          default:
              break;
      }
      
      
   }
}
