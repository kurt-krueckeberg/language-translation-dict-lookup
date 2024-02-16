<?php
declare(strict_types=1);
namespace Vocab;

// TODO: Get rid of AbstractDefinitionsIterator and put its iteation-related code in thie class.

abstract class DBWordBase implements \Iterator {  

   static private $stmts = [];
    
   protected \PDO $pdo;
   
   protected \ArrayIterator $aiter;

   // TODO: Combinge these two queries into one. 
   private static $sql_expressions_count = "select defns.id as defn_id, defns.defn, count(exprs.id) as expressions_count from 
     defns 
left join
     exprs
         on exprs.defn_id=defns.id 
     where defns.word_id=:word_id
  group by defns.id
    order by defn_id asc;";

   private static $sql_get_expressions = "select exprs.id as epxrs_id, exprs.expr from 
   defns
 JOIN 
   exprs
   on defns.id=exprs.defn_id
where defns.word_id=:word_id;"

   private static int $word_id = -1;
   
   private array $expresion_cnt;

   protected get_sql(string $str) : string
   {
      return match($str) {
        'sql_expressions_count' => self::$sql_expressions_count,
        'sql_get_expressions' => self::$sql_get_expressions
      };  
   }

   protected bind(\PDOStatement $stmt)
   {
       $this->stmt(':word_id', self::$word_id, \PDO::PARAM_INT);
   }
    
   function __construct(\PDO $pdo, int $word_id)
   {
      $this->pdo = $pdo;
 
      // get count of expressions
      self::$word_id = $word_id;     

      $exprs_cnt_stmt = $this->DBWordBase::get_stmt($pdo, 'sql_expressions_count');
      
      $this->expressions_cnt = $exprs_cnt_stmt->fetchALL(\PDO::FETCH_ASSOC)l      

      $this->iter = new \ArrayIterator($arr);
   }
   
   protected function get_stmt(\PDO $pdo, string $str) : \PDOStatement
   {     
      if (!isset(self::$stmts[$str])) {
                   
         $stmt = $this->pdo->prepare( $this->get_sql($str) ); 

         $this->bind($stmt, $str);

         self::$stmts[$str] = $stmt;
      }

      return self::$stmts[$str];
   }
        
   abstract protected function bind(\PDOStatement $pdo, string $str) : void; 

   abstract protected function get_sql(string $str) : string;
  
   abstract protected function get_current(array $target) : DefinitionInterface | false;

   function current() : DefinitionInterface | false
   {
     $ele =  parent::current();

     return ($ele === false) ? $ele : $this->get_current($ele);
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
