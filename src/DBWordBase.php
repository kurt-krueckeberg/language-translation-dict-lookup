<?php
declare(strict_types=1);
namespace Vocab;

class DBWordBase implements \IteratorAggregate {  

   static private $stmts = [];
    
   protected \PDO $pdo;
      
   private static $sql_defns_expressions_count = "select defns.id as defn_id, defns.defn as definitions, count(exprs.id) as expressions_count from 
     defns 
left join
     exprs
         on exprs.defn_id=defns.id 
     where defns.word_id=:word_id
  group by defns.id
    order by defn_id asc;";

   private static $sql_get_expressions = "select exprs.id as epxrs_id, exprs.expr as expressions from 
   defns
 JOIN 
   exprs
   on defns.id=exprs.defn_id
where defns.word_id=:word_id;";

   //--private static string $sql_wordselect  = "select id, pos from words as w where w.word=:word";

   protected static int $word_id = -1;
   protected static string $word = '';
   
   protected array $expresions_cnt;
   
   protected array $expressions;
   
   protected array $definitions;
      
   function __construct(\PDO $pdo, int $word_id)
   {
      $this->pdo = $pdo;
           
      $exprs_cnt_stmt = $this->get_stmt($pdo, 'sql_defns_expressions_count');
      
      // Get expressions count
      self::$word_id = $word_id;     

      $exprs_cnt_stmt->execute();
      
      $result = $exprs_cnt_stmt->fetchALL(\PDO::FETCH_ASSOC);
      
      $this->definitions = array_column($result, 'definitions');

      $this->expressions_cnts = array_column($result, 'expressions_count');
      
      $get_expressions = $this->get_stmt($pdo, 'sql_get_expressions');
      
      $get_expressions->execute();
      
      $expressions = $get_expressions->fetchAll(\PDO::FETCH_ASSOC);
      
      $this->expressions = array_column($expressions, 'expressions');
   }
     
   static private function generator_defn_exprs(array $definitions, array $expressions, array $exprs_counts) : \Traversable
   {
      $offset = 0;

      foreach ($definitions as $index => $value) {

         yield $value => array_slice($expressions, $offset, $exprs_counts[$index]);

         $offset += $exprs_counts[$index];
     }
   }

   protected function get_stmt(\PDO $pdo, string $str) : \PDOStatement
   {     
      if (!isset(self::$stmts[$str])) {
                   
         $stmt = $this->pdo->prepare( $this->get_sql($str) ); // <-- BUG: Calls derived class

         $this->bind($stmt, $str);

         self::$stmts[$str] = $stmt;
      }

      return self::$stmts[$str];
   }

   protected function get_sql(string $str) : string
   {
      return match($str) {
        'sql_defns_expressions_count' => self::$sql_defns_expressions_count,
        'sql_get_expressions' => self::$sql_get_expressions,
      };  
   }

   protected function bind(\PDOStatement $stmt, string $str='') : void
   {
       match ($str) {
         'sql_wordselect' => $stmt->bindParam(':word', $this->word, \PDO::PARAM_STR),         
         default => $stmt->bindParam(':word_id', self::$word_id, \PDO::PARAM_INT)
       };
   }
       
   public function getIterator() : \Traversable
   {
       return DBWordBase::generator_defn_exprs($this->definitions, $this->expressions, $this->expressions_cnts);
   }
}
