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

/*
   protected function prepare_and_bind(\PDO $pdo, string $str) : \PDOStatement
   {    
      $sql =  match ($str) {
          
        'sql_verb' => self::$sql_verb,
        'sql_count' => self::$sql_count        
      };
      
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':word_id', self::$word_id, \PDO::PARAM_INT);
      
      return $stmt;
   }
*/
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
      
      return $stmt;
   }

   public function __construct(\PDO $pdo, int $word_id)
   {
      $this->pdo = $pdo;
                  
      $verb_stmt = parent::get_stmt($pdo, 'sql_verb');
            
      self::$word_id = $word_id;     
    
      $exprs_count = parent::get_stmt($pdo, 'sql_count');

      $rc = $verb_stmt->execute();

      $this->rows = $verb_stmt->fetchAll(\PDO::FETCH_ASSOC);
      
      $rc = $exprs_count->execute();

      $this->expr_counts = $exprs_count->fetchAll(\PDO::FETCH_ASSOC);      
   }
}
