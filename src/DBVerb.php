<?php
declare(strict_types=1);
namespace Vocab;

class DBVerb extends DBWordBase implements VerbInterface  {  

   /*
    * Get the verb, its id, part of speech, its conjugation, its definitions and any expressions associated with the definition.
    */
private static $sql_verb = "select w.id as word_id, w.word, w.pos, tenses.conjugation as conjugation, defns.id as defns_id, defns.defn, exprs.id as exprs_id, exprs.expr, translated_expr from
     words as w
join 
    shared_conjugations as v  on w.id=v.word_id
join
    conjugations as tenses on tenses.id=v.conj_id
join 
     defns on defns.word_id=w.id
left join
     exprs on exprs.defn_id=defns.id 
where w.id=:word_id";

  /*
   * Get the expression count for each definition. Some definitions have no expressions. These are no included
   * in the grouped rows of the joined defns and exprs tables.
   * 
   * This result is need for the definitions iterator returned definitions(). This returned 
   */
   private static $sql_count = "select defns.id as defn_id, defns.defn, count(exprs.id) as expressions_count from 
     defns 
 join
     exprs
         on exprs.defn_id=defns.id 
     where defns.word_id=:word_id
  group by defns.id
    order by defn_id asc;";
   
   /*
    * private non-sql string members
    */
      
   static int $word_id = -1;
      
   private \PDO $pdo;
   
   private array $rows;
   
   private array $expr_counts;

   protected function get_sql(string $str) : string
   {    
      return match ($str) {
          
        'sql_verb' => self::$sql_verb,
        'sql_count' => self::$sql_count        
      };
   }

   protected function bind(\PDOStatement $stmt, string $str) : void 
   {    
      $stmt->bindParam(':word_id', self::$word_id, \PDO::PARAM_INT);
   }

   public function __construct(\PDO $pdo, int $word_id)
   {
      parent::__construct($pdo);
                  
      $verb_stmt = parent::get_stmt($pdo, 'sql_verb');
            
      self::$word_id = $word_id;     
    
      $exprs_count = parent::get_stmt($pdo, 'sql_count');

      $rc = $verb_stmt->execute();

      $this->rows = $verb_stmt->fetchAll(\PDO::FETCH_ASSOC);

      $this->defns = $this->rows['defn']; // unique definitions
      
      $rc = $exprs_count->execute();

      $this->expr_counts = $exprs_count->fetchAll(\PDO::FETCH_ASSOC);      
   }

   function conjugation() : string
   {
     $this->rows[0]['conjugation'];
   }

   function word_defined() : string
   {
     $this->rows[0]['word'];
   } 

   function  get_pos() : Pos
   {
      return Pos::fromString($this->row[0]['pos']);
   }

  /*
   * Use the count of expressions (and the associated defns.id) from the $sql_count query onIterator.
   */
   function definitions() : AbstractDefinitionsIterator // <-- TODO: This needs to be in an interface. Is it?
   {
       return new DBDefinitionsIterator($this->rows, $this->expr_counts);
   }
}
