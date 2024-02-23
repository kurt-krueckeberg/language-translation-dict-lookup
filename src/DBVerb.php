<?php
declare(strict_types=1);
namespace Vocab;

class DBVerb extends DBWordBase implements VerbInterface {  

   /*
    * Get the verb's id, part of speech, its conjugation. The base class will get its definitions and any 
    * associated expressions.
    */
protected static $sql_verb = "select w.id as word_id, w.word, w.pos, tenses.conjugation as conjugation from
     words as w
join 
    shared_conjugations as v  on w.id=v.word_id
join
    conjugations as tenses on tenses.id=v.conj_id
where w.id=:word_id";

   static int $word_id = -1;
      
   protected \PDO $pdo;
   protected string $word_defined;
   
   private array $rows;
   
   private array $expr_counts;

   public function __construct(\PDO $pdo, Pos $pos, string $word, int $word_id)
   {
      parent::__construct($pdo, $word_id);
      
      $this->pos = $pos;

      $this->word_defined = $word;
                  
      $verb_stmt = $this->get_stmt($pdo, 'sql_verb');
            
      self::$word_id = $word_id;     
   
      $rc = $verb_stmt->execute();

      $this->rows = $verb_stmt->fetchAll(\PDO::FETCH_ASSOC);
   }
   
   public function get_pos() : Pos
   {
       return $this->pos;
   }

   protected function bind(\PDOStatement $stmt, string $str='') : void 
   {    
       match($str) {
          
        'sql_verb' => $stmt->bindParam(':word_id', self::$word_id, \PDO::PARAM_INT),
        default => parent::bind($stmt, $str)
      };
   }

   public static function GeneratorIteraor(array $rows, int $expression_counts) : \Iterator
   {
       print_r($rows);
   }
   
   function conjugation() : string
   {
     return $this->rows[0]['conjugation'];
   }

   function word_defined() : string
   {
     return $this->word_defined;
   } 
}
