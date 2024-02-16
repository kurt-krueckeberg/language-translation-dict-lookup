<?php
declare(strict_types=1);
namespace Vocab;

class VerbsConjugation extends Table implements TableInsertInterface { 
    
   private \PDOStatement $insert_stmt; 
   private string $conjugation = '';
   private int $word_id = 0;

   private static $insert_sql = "insert into verb_conjugations(conjugation, word_id) values(:conjugation, :word_id)";

   public function __construct(\PDO $pdo)
   {
      parent::__construct($pdo, $this);
      
      $this->insert_stmt = parent::get_statement($this);
   }

   public function prepare(\PDO $pdo) : \PDOStatement
   {
      $stmt = $pdo->prepare(self::$insert_sql);

      $stmt->bindParam(':conjugation', $this->conjugation, \PDO::PARAM_STR);

      $stmt->bindParam(':word_id', $this->word_id, \PDO::PARAM_INT); 

      return $stmt;  
   }

   public function insert(WordInterface $wrface, int $id=-1) : int
   {
       $this->word_id = $id;
 
       $this->conjugation = $wrface->conjugation();

       $this->insert_stmt->execute();

       $id = (int) $this->pdo->lastInsertId();

       return $id;
   }
}
