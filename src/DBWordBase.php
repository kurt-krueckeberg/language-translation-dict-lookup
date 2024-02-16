<?php
declare(strict_types=1);
namespace Vocab;

class DBWordBase implements /* \Iterator */ \IteratorAggregate {  

   static private $stmts = [];
    
   protected \PDO $pdo;
   
   protected \ArrayIterator $iter;

   // TODO: Combinge these two queries into one. 
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

   protected static int $word_id = -1;
   
   protected array $expresion_cnt;
   
   protected array $expressions;
   
   protected array $definitions;
   
   protected function get_stmt(\PDO $pdo, string $str) : \PDOStatement
   {     
      if (!isset(self::$stmts[$str])) {
                   
         $stmt = $this->pdo->prepare( $this->get_sql($str) ); 

         $this->bind($stmt, $str);

         self::$stmts[$str] = $stmt;
      }

      return self::$stmts[$str];
   }

   protected function get_sql(string $str) : string
   {
      return match($str) {
        'sql_defns_expressions_count' => self::$sql_defns_expressions_count,
        'sql_get_expressions' => self::$sql_get_expressions
      };  
   }

   protected function bind(\PDOStatement $stmt) : void
   {
       $stmt->bindParam(':word_id', self::$word_id, \PDO::PARAM_INT);
   }
    
   function __construct(\PDO $pdo, int $word_id)
   {
      $this->pdo = $pdo;
 
      // get count of expressions
      self::$word_id = $word_id;     

      $exprs_cnt_stmt = $this->get_stmt($pdo, 'sql_defns_expressions_count');
      
      $exprs_cnt_stmt->execute();
      
      $result = $exprs_cnt_stmt->fetchALL(\PDO::FETCH_ASSOC);
      
      $this->definitions = array_column($result, 'definitions');

      $this->iter = new \ArrayIterator($this->definitions);
      
      $this->expressions_cnt = array_column($result, 'expressions_count');
      
      $get_expressions = $this->get_stmt($pdo, 'sql_get_expressions');
      
      $get_expressions->execute();
      
      $expressions = $get_expressions->fetchAll(\PDO::FETCH_ASSOC);
      
      $this->expressions = array_column($expressions, 'expressions');
   }
     
   function current() : DefinitionInterface | false
   {
     $ele =  $this->iter->current();

     if ($ele === false) return false;

     $index = $this->iter->key();

     $this->expressions_cnt[$index]; // todo: I also need both the offset from the start of the first element to the current set of exprssions
                                     // and the total number of expressions. 
     
     return new DBDefinition($this->iter[$index], array() ); // <-- TODO: 2nd parameter not yet right per comment above.
   }

   function next() : void
   {
      $this->iter->next();
   }

   function valid() : bool
   {
      return $this->iter->valid();
   }

   function key() : int | null
   {
      return $this->iter->key();
   }

   function rewind() : void
   {
      $this->iter->rewind();
   }
}
