<?php
declare(strict_types=1);
namespace Vocab;

class CliDatabase { 

   private \PDO $pdo;

   private string $locale;

   private static $sql_word_fetch = "select 1 from words where word=:new_word";
   
   private \PDOStatement $word_fetch;

   private string $word;
      
   public function __construct(Config $config)
   {
      $cred = $config->get_db_credentials();
     
      $this->pdo = new \PDO($cred["dsn"], $cred["user"], $cred["password"]); 
       
      $this->locale = $config->get_locale();

      $this->word_exists_stmt = $pdo->prepare(self::$sql_word_fetch); 
      
      $this->word_fetch->bindParam(':word', $this->word, \PDO::PARAM_STR); 
   }

   function fetch_db_word($word) : array | false // array(WordInterface $wface,int $word_id) | false
   {
      $fetch = $this->get_table('FetchWord');
      
      list($pos, $word_id) = $fetch($word);
      
      if ($pos === false) return false;

      $result= match($pos) {
         
         Pos::Verb => new DBVerb($this->pdo, $pos, $word, $word_id), //<--
         Pos::Noun => new DBNoun($this->pdo, $pos, $word, $word_id),
         default => new DBWord($this->pdo, $word_id)        
         
      };
           
      return array($result, $word_id);
   }
   
   function fetch_samples(int $word_id) : \Traversable | false
   {
       // Retrieve all the samples, if any, from the word definition in $wrface
       $fetch = $this->get_table('FetchSamples');
      
       return $fetch($word_id);
   }

   function save_samples(string $word, TranslateInterface $translator, \Traversable $sentences_iter) : bool
   {
      $samplesTbl = new SamplesTable($this->pdo);

      $prim_key = $this->word_prim_keys[$word];

      foreach ($sentences_iter as $sentence) {
          
        $rc = $samplesTbl->insert($sentence, $translator->translate($sentence, 'en', 'de'), $prim_key);  
      }
      
      return true;
   }
}
