<?php
declare(strict_types=1);
namespace Vocab;

class Database extends DatabaseBase implements InserterInterface { 

   private \PDO $pdo;

   private string $locale;

   private InserterInterface $inserter;

   private array $tables;

   private $word_prim_keys = []; // maps words to primary keys
   private $conjugations_prim_keys = []; // maps words to primary keys
   
   public function __construct(Config $config)
   {
     $cred = $config->get_db_credentials();
     
     $this->pdo = new \PDO($cred["dsn"], $cred["user"], $cred["password"]); 
       
     parent::__construct($this->pdo);
       
     $this->locale = $config->get_locale();

     $this->inserter = new WordResultInserter($this);
   }
     
   public function insert_noun(WordInterface $deface) : int
   {
     $id = $this->insert_word($deface);
  
     $nounsDataTbl = $this->get_table('NounsDataTable');
  
     $id = $nounsDataTbl->insert($deface, $id); 
  
     return $id;
   }

   public function insert_verb(WordInterface $wrface) : int // returns words.id
   {
     $word_id = $this->insert_word($wrface);
     
     $conjTensesTbl = $this->get_table('ConjugationsTable');

     $conj_id = $conjTensesTbl->insert($wrface, $word_id);
     
     $this->conjugations_prim_keys[$wrface->word_defined()] = $conj_id;  

     $conjugatedVerbsTbl = $this->get_table('SharedConjugationsTable');

     $conjugatedVerbsTbl->insert($conj_id, $word_id);
     
     return $word_id;
   }

   public function insert_related_verb(WordInterface $wrface) : int
   {
      $word_id = $this->insert_word($wrface);

      $conjugatedVerbsTbl = $this->get_table('SharedConjugationsTable');
       
      $conj_id = $this->conjugations_prim_keys[$wrface->get_main_verb()]; 
  
      $conjugatedVerbsTbl->insert($conj_id, $word_id);
      
      return $conj_id;
   }

   public function insert_word(WordInterface $wrface) : int
   {
     $word_tbl = $this->get_table('WordTable');

     $defns_tbl = $this->get_table('DefnsTable');

     $expr_tbl = $this->get_table('ExpressionsTable');

     $word_id = $word_tbl->insert($wrface); 

     $this->word_prim_keys[$wrface->word_defined()] = $word_id;
     
     foreach($wrface as $defn => $expressions) {

        $defn_id = $defns_tbl->insert($defn, $word_id);

        foreach ($expressions as $expr) {

          $expr_tbl->insert($expr, $defn_id);
        } 
     }

    return $word_id; 
   }

   public function save_lookup(WordInterface $wdResultFace)   
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

   function fetch_word($word) : WordInterface | false
   {
      $fetch = new FetchWord($this->pdo);
      
      list($pos, $word_id) = $fetch($word);
      
      if ($pos === false) return false;

      $result= match($pos) {
         
         Pos::Verb => new DBVerb($this->pdo, $pos, $word, $word_id),
         Pos::Noun => new DBNoun($this->pdo, $word_id),
         default => new DBWord($this->pod, $word_id)        
         
      };
           
      return $result;
   }
   
   function fetch_samples(WordInterface $wrface) : \Traversable
   {
       // Retrieve all the samples, if any, from the word definition in $wrface
   }

   function save_samples(string $word, \Iterator $sentences_iter) : bool
   {
      $samplesTbl = new SamplesTable($this->pdo);

      $prim_key = $this->word_prim_keys[$word];

      foreach ($sentences_iter as $sentence) {
          
        $rc = $samplesTbl->insert($sentence, $prim_key);  
      }
      
      return true;
   }
}
