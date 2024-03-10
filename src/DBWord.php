<?php
declare(strict_types=1);
namespace Vocab;

class DBWord implements WordInterface {  
  
   private array $row; 
 
   static private $stmts = [];
    
   protected \PDO $pdo;

   // This doesn't work for get an entire verb family.      
   private static $sql_defns_expressions_count = "select defns.id as defn_id, defns.defn as definitions, count(exprs.id) as expressions_count from 
     defns 
left join
     exprs
         on exprs.defn_id=defns.id 
     where defns.word_id=:word_id
  group by defns.id
    order by defn_id asc;";

   private static $sql_get_expressions = "select exprs.source as source, exprs.target as target from 
   defns
 join 
   exprs
   on defns.id=exprs.defn_id
where defns.word_id=:word_id;";

   protected static int $word_id = -1;
      
   protected array $expresions_cnt;
   
   protected array $expressions;
   
   protected array $definitions;

   use get_stmt_trait;
  
   public function __construct(\PDO $pdo, array $row) 
   {
      $this->row = $row;

      $result = $this->fetch_sql_defns($pdo, $row['word_id']);      

      $this->definitions = array_column($result, 'definitions');

      $this->expressions_cnts = array_column($result, 'expressions_count');

      $this->expressions = $this->fetch_expressions($pdo);
   }

   private function fetch_sql_defns(\PDO $pdo, int $word_id) : array
   {
      $exprs_cnt_stmt = $this->get_stmt($pdo, 'sql_defns_expressions_count');
      
      // Get expressions count
      self::$word_id = $word_id;     

      $exprs_cnt_stmt->execute();
      
      return $exprs_cnt_stmt->fetchALL(\PDO::FETCH_ASSOC);
   }

   private function fetch_expressions(\PDO $pdo) : array
   {
      $get_expressions = $this->get_stmt($pdo, 'sql_get_expressions');
      
      $get_expressions->execute();
      
      $this->expressions = $get_expressions->fetchAll(\PDO::FETCH_ASSOC);
   }  

   function get_pos() : Pos
   {
      return Pos::fromString($this->row['pos']); 
   }

   function word_defined() : string
   {
      return $this->row['word']; 
   }

   static private function generator_defn_exprs(array $definitions, array $expressions, array $exprs_counts) : \Traversable
   {
      $offset = 0;

      foreach ($definitions as $index => $definition) {

         // Return key/value pair that can be used like so: foreach ($iter as $defition => $expressions)
         yield $definition => array_slice($expressions, $offset, $exprs_counts[$index]);

         $offset += $exprs_counts[$index];
     }
   }
           
   protected function bind(\PDOStatement $stmt) : void
   {  
      /*
       * Both SQL expressions self::$sql_get_expressions and self::$sql_defns_expressions_count use self::$word_id as
       * the bound parameter.
       */ 
      $stmt->bindParam(':word_id', self::$word_id, \PDO::PARAM_INT);           
      
      /*
      match($str) {

       'sql_defns_expressions_count', 'sql_get_expressions' => $stmt->bindParam(':word_id', self::$word_id, \PDO::PARAM_INT)          
      };
       */ 
   }
       
   public function getIterator() : \Traversable
   {
       return DBWord::generator_defn_exprs($this->definitions, $this->expressions, $this->expressions_cnts);
   }
   
   function get_word_id() : int
   {
       return self::$word_id;
   }
}
