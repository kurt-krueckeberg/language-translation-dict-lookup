<?php
declare(strict_types=1);
namespace Vocab;

class DBVerb  {  

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
      
   static private $stmts = ['verb_info' => null, 'exprs_count' => null];
   
   static int $word_id = -1;
      
   private \PDO $pdo;
   
   private array $rows;
   
   private array $expr_counts;

   private function get_stmt(string $str) : \PDOStatement
   {
      if (!isset(self::$stmts[$str])) {

         $sql = match ($str) {
           'verb_info' => self::$sql_verb,
           'exprs_count' => self::$sql_count
         }; 
         
         self::$stmts[$str] = $this->pdo->prepare($sql);

         self::$stmts[$str]->bindParam(':word_id', self::$word_id, \PDO::PARAM_INT);
      }

      return self::$stmts[$str];
   }

   public function __construct(\PDO $pdo, int $word_id)
   {
      $this->pdo = $pdo;
      
      self::$word_id = $word_id;

      $verb_stmt = $this->get_stmt('verb_info');

      $exprs_count = $this->get_stmt('exprs_count');

      $rc = $verb_stmt->execute();

      $this->rows = $verb_stmt->fetchAll(\PDO::FETCH_ASSOC);
      
      print_r($this->rows);

      $rc = $exprs_count->execute();

      $this->expr_counts = $exprs_count->fetchAll(\PDO::FETCH_ASSOC);
      
      print_r($this->expr_counts);
   }
}
