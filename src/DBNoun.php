<?php
declare(strict_types=1);
namespace Vocab;

class DBNoun extends DBWordBase implements NounInterface {  

   /*
    * Get the noun's id, part of speech, gender and plural. The base class will get its definitions and any 
    * associated expressions.
    */
protected static $sql_noun = "select n.gender as gender, n.plural as plural from
     words as w
join
    nouns_data as n on w.id=n.word_id
where w.id=:word_id";

   static int $word_id = -1;
      
   protected \PDO $pdo;
   
   private array $rows;
   
   private string $word_defined;
   
   private Pos $pos;
   
   public function __construct(\PDO $pdo, Pos $pos, string $word, int $word_id)
   {
      parent::__construct($pdo, $word_id);
      
      $this->word_defined = $word;
      
      $this->pos = $pos;

      $noun_stmt = parent::get_stmt($pdo, 'sql_noun');
            
      self::$word_id = $word_id;     
   
      $rc = $noun_stmt->execute();

      $this->rows = $noun_stmt->fetchAll(\PDO::FETCH_ASSOC);
   }
   
   public function get_pos() : Pos
   {
       return $this->pos;
   }

   protected function bind(\PDOStatement $stmt, string $str='') : void 
   {    
      match($str) {
          
       'sql_noun' => $stmt->bindParam(':word_id', self::$word_id, \PDO::PARAM_INT),
      
       default => parent::bind($stmt, $str)
      };
   }

   public static function GeneratorIteraor(array $rows, int $expression_counts) : \Iterator
   {
       print_r($rows);
   }
   
   function word_defined(): string 
   {
      return $this->word_defined;
   }
   
   function plural() : string
   {
     return $this->rows[0]['plural'];
   }

   function gender() : Gender
   {
     return Gender::fromString($this->rows[0]['gender']);
   } 
}
