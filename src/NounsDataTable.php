<?php
declare(strict_types=1);
namespace Vocab;

class NounsDataTable implements TableInsertInterface { 
     
   private \PDOStatement $insert; 

   private static string $insert_sql = "insert into nouns_data(gender, plural, word_id) values(:gender, :plural, :word_id)"; 

   private string $gender = '';
   private string $plural = '';
   private int $word_id = -1;

   private \PDO $pdo;
 
   public function __construct(\PDO $pdo)
   {
      $this->pdo = $pdo;

      $this->insert = $pdo->prepare(self::$insert_sql); 

      $this->insert->bindParam(':gender', $this->gender, \PDO::PARAM_STR);
 
      $this->insert->bindParam(':plural', $this->plural, \PDO::PARAM_STR); 

      $this->insert->bindParam(':word_id', $this->word_id, \PDO::PARAM_INT); 
   }

   public function insert(WordResultInterface $deface, int $word_id = -1) : int
   {
       $this->gender = $deface->gender()->value;
       
       $this->plural = $deface->plural();

       $this->word_id = $word_id;
       
       $this->insert->execute();
      
       return (int) $this->pdo->lastInsertId();
   }
}
