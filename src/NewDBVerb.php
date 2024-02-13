<?php
declare(strict_types=1);
namespace Vocab;

class NewDBVerb extends DBWordBase  {  

private static $sql_verb = "select w.id as word_id, w.pos, tenses.conjugation, defns.id, defns.defn, exprs.id as exprs_id, exprs.expr, translated_expr from
     words as w
join 
    conjugated_verbs as v  on w.id=v.word_id
join
    conjugated_tenses as tenses on tenses.id=v.conj_id
join 
     defns on defns.word_id=w.id
left join
     exprs on exprs.defn_id=defns.id 
where w.id=:word_id";
   
private static $sql_count = "select defns.id as defn_id, count(*) as expressions_count from 
defns 
left join
     exprs on exprs.defn_id=defns.id 
     WHERE defns.word_id=:word_id
group by defns.id order by defn_id asc;";
      
   //static private $stmts = ['verb_info' => null, 'exprs_count' => null];
   
   static int $word_id = -1;
      
   private \PDO $pdo;
   
   private array $rows;
   
   private array $expr_counts;

   protected function do_bind($stmt_key, \PDOStatement $stmt) : void
   {
      var_dump($stmt);
      $rc =  match ($stmt_key) {
          
        'sql_verb' => $stmt->bindParam(":word_id", self::$word_id, \PDO::PARAM_INT),     
        'sql_count' => $stmt->bindParam(":word_id", self::$word_id, \PDO::PARAM_INT)
      };   
   }

   public function __construct(\PDO $pdo, int $word_id)
   {
      $this->pdo = $pdo;
      
      self::$word_id = $word_id;      
      
      $verb_stmt = parent::get_stmt($pdo, 'sql_verb');
    
      $exprs_count = parent::get_stmt($pdo, 'sql_count');

      $rc = $verb_stmt->execute();

      $this->rows = $verb_stmt->fetchAll(\PDO::FETCH_ASSOC);
      
      print_r($this->rows);

      $rc = $exprs_count->execute();

      $this->expr_counts = $exprs_count->fetchAll(\PDO::FETCH_ASSOC);
      
      print_r($this->expr_counts);
   }
}
