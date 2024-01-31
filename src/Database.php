<?php
declare(strict_types=1);
namespace Vocab;

class Database extends DatabaseBase implements InserterInterface { 

   private \PDO $pdo;

   private string $locale;

   private InserterInterface $inserter;

   private array $tables;

   private $prim_keys = []; // maps words to primary keys
   
   public function __construct(Config $config)
   {
     $cred = $config->get_db_credentials();
     
     $this->pdo = new \PDO($cred["dsn"], $cred["user"], $cred["password"]); 
       
     parent::__construct($this->pdo);
       
     $this->locale = $config->get_locale();

     $this->inserter = new WordResultInserter($this);
   }
     
   public function insert_noun(WordResultInterface $deface) : int
   {
     $id = $this->insert_word($deface);
  
     $nounsDataTbl = $this->get_table('NounsDataTable');
  
     $id = $nounsDataTbl->insert($deface, $id); 
  
     return $id;
   }

   public function insert_verb(WordResultInterface $wrface) : int
   {
     $word_id = $this->insert_word($wrface);
     
     $conjTensesTbl = $this->get_table('ConjugatedTensesTable');

     $conj_id = $conjTensesTbl->insert($wrface, $word_id);

     $conjugatedVerbsTbl = $this->get_table('ConjugatedVerbsTable');

     $conjugatedVerbsTbl->insert($conj_id, $word_id);

     return $conj_id;
   }

   public function insert_related_verbs(WordResultInterface $wrface) : int
   {
      $conj_id = $this->insert_verb($wrface);

      $related = [];

      foreach($wrface as $key => $verbResult) {

         echo "Verb to be inserted: " . $verbResult->word_defined() . ".\n"; 
         
         $id = $this->insert_word($verbResult); 

         $related[] = $id;
      }
 
      $conjugatedVerbsTbl = $this->get_table('ConjugatedVerbsTable');

      foreach ($related as $word_id) {
         
          $conjugatedVerbsTbl->insert($conj_id, $word_id);
      }  
      
      return $conj_id;
   }

   public function insert_word(WordResultInterface $wrface) : int
   {
     $word_tbl = $this->get_table('WordTable');

     $defns_tbl = $this->get_table('DefnsTable');

     $expr_tbl = $this->get_table('ExpressionsTable');

     $word_id = $word_tbl->insert($wrface); 

     $this->prim_keys[$wrface->word_defined()] = $word_id;
     
     foreach($wrface->definitions() as $defn) {

        $defn_id = $defns_tbl->insert($defn->definition(), $word_id);

        foreach ($defn->expressions() as $expr) {

          $expr_tbl->insert($expr, $defn_id);
        } 
     }

    return $word_id; 
   }

   public function save_lookup(WordResultInterface $wdResultFace)   
   {
      $wdResultFace->accept($this->inserter); 
     
      return true;
   } 
   
   private function get_table(string $table_name) : mixed
   {
      $className = "Vocab\\$table_name";
  
      if (isset($this->tables[$className]) === false) {

         $instance = new $className($this->pdo);

         $this->tables[$className] = $instance;
     } 

      return $this->tables[$className];
   }

   function insert_examples(string $word, SentencesIterator $iter) : bool
   {
      $samplesTbl = new SampleTable($this->pdo);

      $prim_key = $this->prim_keys[$word];

      foreach ($iter as $sentence) {
          
        $rc = $samplesTbl->insert($sentence, $prim_key);  
      }
   }
}
