<?php
declare(strict_types=1);
namespace Vocab;

class DBVerb extends /* DBWordBase */ implements VerbInterface {  

   /*
    * Get the verb's id, part of speech, its conjugation. The base class will get its definitions and any 
    * associated expressions.
    */
protected static $sql_verb = "select w.id as word_id, w.word, w.pos, tenses.conjugation as conjugation from
     words as w
join 
    verbs_conjs as v  on w.id=v.word_id
join
    conjs as tenses on tenses.id=v.conj_id
where w.id=:word_id";

protected static $sql_verb_family_word = "select w_id, w_word, conjugation FROM
(select vc.conj_id as outer_conj_id, vc.word_id as outer_word_id from verbs_conjs as vc where vc.word_id=:word_id) as Y
inner join
(select w.id as w_id,
     w.word as w_word,
     conjs.conjugation as conjugation,
     vc.conj_id as inner_conj_id     
 from words as w
 inner join
  verbs_conjs as vc ON vc.word_id=w.id
 inner join
  conjs ON conjs.id=vc.conj_id
) as X
on Y.outer_conj_id=X.inner_conj_id"

/*
 * This retrieves a verb family: the words.id, the cpnjugation, the definitions and any associated expressions. 
protected static $sql_verb_family = "select * FROM
(select vc.conj_id as outer_conj_id, vc.word_id as outer_word_id from verbs_conjs as vc where vc.word_id=:word_id) as Y
inner join
(select w.id as w_id,
     w.word as w_word,
     conjs.conjugation as conjugation,
     vc.conj_id as inner_conj_id,
     d.word_id as defns_word_id,
     d.defn as definition,
     e.source,
     e.target
 from words as w
 inner join
  verbs_conjs as vc ON vc.word_id=w.id
inner join
  conjs ON conjs.id=vc.conj_id
inner join
  defns as d ON w.id=d.word_id
left join
  exprs as e ON e.defn_id=d.id) as X
on Y.outer_conj_id=X.inner_conj_id";
 */

   static int $word_id = -1;
      
   protected \PDO $pdo;
   protected string $word_defined;
   
   private array $rows;
   
   private array $expr_counts;

   public function __construct(\PDO $pdo, Pos $pos, string $word, int $word_id)
   {
      /*
      parent::__construct($pdo, $word_id);
       */
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

   function getIterator() : \Iterator
   {
      // call the generator that is either a static method of an implementing class--that may already exist in part or in full.
      // The generator will provide iteration over all the definitions. What iterates of the verb family?
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
