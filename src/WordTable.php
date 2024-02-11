<?php
declare(strict_types=1);
namespace Vocab;

/*
 * Try to find the word. 
 * If found, if noun, get gender and plural; if verb, get conjugation.
 * 
 * Return a base class similiar to WordResultInterface -- which NounResultInterface and VerbResultInterface extend.
 * Remove 'Result' from the name of these interfaces.
 * 
 */

class FetchWord  {  
   
   //private \PDO $pdo;
   private \PDOStatement $word_select; 
   private static $sql_wordselect = "select from words as w where w.word=:word";

   private string $word = '';
   
   private \PDO $pdo;
   
   private POS $pos;

   public function __construct(\PDO $pdo)
   {
      $this->pdo = $pdo;

      $this->word_select = $pdo->prepare(self::$sql_wordselect);
      
      $this->insert_stmt->bindParam(':word', $this->word, \PDO::PARAM_STR);     
   }

   function find(string $word) : WordResultInterface | false
   {
      $this->word = $word;
      
      $rc = $this->word_select->execute();
      
      if ($rc == false)
          return false;
      
      $word = $this-word_select->fetch(\PDO::FETCH_ASSOC);
      
      $this->pos = POS::fromString($word['pos']);
      
      switch ($word['pos']) {
          
          'noun':
              
              break;
          
          'verb':
              // select verb inflection from verb 
              break;
          
          default:
              break;
      }
      
      
   }
}
