<?php
declare(strict_types=1);
namespace Vocab;

class Database extends DbBase implements InserterInterface { 

   private \PDO $pdo;

   private string $locale;

   private InserterInterface $inserter;

   private $word_prim_keys = []; // maps words to primary keys
   
   public function __construct(\PDO $pdo, string $locale)
   {
     $this->pdo = $pdo;
 
     parent::__construct($this->pdo);
       
     $this->locale = $locale;

     $this->inserter = new WordResultInserter($this);
   }
     
   public function insert_noun(WordInterface $deface) : int
   {
     $id = $this->insert_word($deface);
  
     $nounsDataTbl = $this->get_table('NounsDataTable');
  
     $word_id = $nounsDataTbl->insert($deface, $id); 
  
     return $word_id;
   }

   public function insert_verb(WordInterface $wrface) : int // returns words.id
   {
     $word_id = $this->insert_word($wrface);
     
     $conjsTbl = $this->get_table('ConjugationsTable');

     $conj_id = $conjsTbl->insert($wrface, $word_id);
     
     return $word_id;
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
      /*
       *  Pass the Inserter to WordInterface, which will then itself (this)
       *  to the appropriate Database insertion method: either
       *  insert_word, insert_noun or insert_verb.
       */
      $wdResultFace->accept($this->inserter); 

      return true;
   } 
   
   /*
    * Returns an Iterator that returns the word, its defns and associated expressions.
    */

   function fetch_db_word($word) : \Iterator | false
   {
      $fetch = $this->get_table('FetchWord'); 
      
      $row = $fetch($word); 
      
      if ($row === false) 
          return false; 

      $creator = new CreateDBWordResultIterator($this->pdo, $row);

      return $creator->getIterator(); 
   }
   
   function fetch_db_samples(int $word_id) : \Traversable | false
   {
       // Retrieve all the samples, if any, from the word definition in $wrface
       $fetchSamples = $this->get_table('FetchSamples');
      
       return $fetchSamples($word_id);
   }

   function save_sample(string $word, string $sentence, string $translation) : bool
   {
      $samplesTbl = $this->get_table('SamplesTable'); 
   
      /*
       * Get the word's primary key, its `words.id`, saved by: 
       *   insert_word(WordInterface).
       */
      $prim_key = $this->word_prim_keys[$word];

      $samplesTbl->insert($sentence, $translation, $prim_key);                  
      
      return true;
   }
}
