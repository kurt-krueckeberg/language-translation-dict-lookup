<?php
declare(strict_types=1);
namespace Vocab;

class DBVerb extends DBWordBase implements VerbInterface {  

   /*
    * Get the verb, its id, part of speech, its conjugation, its definitions and any expressions associated with the definition.
    */
private static $sql_verb = "select w.id as word_id, w.word, w.pos, tenses.conjugation as conjugation from
     words as w
join 
    shared_conjugations as v  on w.id=v.word_id
join
    conjugations as tenses on tenses.id=v.conj_id
where w.id=:word_id";

  /*
   * Get the expression count for each definition. Some definitions have no expressions. 
   * Moved to base class:

   private static $sql_count = "select defns.id as defn_id, defns.defn, count(exprs.id) as expressions_count from 
     defns 
left join
     exprs
         on exprs.defn_id=defns.id 
     where defns.word_id=:word_id
  group by defns.id
    order by defn_id asc;";
*/   
   /*
    * private non-sql string members
    */
      
   static int $word_id = -1;
      
   protected \PDO $pdo;
   
   private array $rows;
   
   private array $expr_counts;

   protected function get_sql(string $str) : string
   {    
      return match ($str) {
          
        'sql_verb' => self::$sql_verb,
        'sql_count' => self::$sql_count,
        default => parent::get_sql($str)         
      };
   }

   protected function bind(\PDOStatement $stmt, string $str='') : void 
   {    
      match($str) {
          
      'sql_erb' => $stmt->bindParam(':word_id', self::$word_id, \PDO::PARAM_INT),
      
      default => parent::bind($stmt, $str)
      };
   }

   public function __construct(\PDO $pdo, Pos $pos, string $word, int $word_id)
   {
      parent::__construct($pdo, $pos, $word, $word_id);
                  
      $verb_stmt = parent::get_stmt($pdo, 'sql_verb');
            
      self::$word_id = $word_id;     
   
      $rc = $verb_stmt->execute();

      $this->rows = $verb_stmt->fetchAll(\PDO::FETCH_ASSOC);

/*
      $exprs_count = parent::get_stmt($pdo, 'sql_count');
      $this->defns = $this->rows['defn']; // unique definitions
      
      $rc = $exprs_count->execute();

      $this->expr_counts = $exprs_count->fetchAll(\PDO::FETCH_ASSOC);      
*/
   }

   public static function GeneratorIteraor(array $rows, int $expression_counts) : \Iterator
   {
       print_r($rows);
   }
   
   function getIterator() : \Iterator
   {
       print_r($this->rows);
       print_r($this->expr_counts);
       
       return DBVerb::GeneratorIteraor($this->rows, $this->expr_counts);
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

}
