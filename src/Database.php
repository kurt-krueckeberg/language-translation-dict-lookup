<?php
declare(strict_types=1);
namespace Vocab;

class Database extends DbBase implements InserterInterface { 

   private \PDO $pdo;

   private string $locale;

   private InserterInterface $inserter;

   private $word_prim_keys = []; // maps words to primary keys
   
   //--private $conjugations_prim_keys = []; // maps words to primary keys
   
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
     
     $conjugatedVerbsTbl = $this->get_table('VerbsConjugationsTable');

     $conjugatedVerbsTbl->insert($conj_id, $word_id);
     
     return $word_id;
   }

   public function insert_related_verb(WordInterface $wrface) : int
   {
      $word_id = $this->insert_word($wrface);

      $conjugatedVerbsTbl = $this->get_table('VerbsConjugationsTable');

      /*
       The code in FetchConjugation is used to get conj_id of the main verb, which is obtained from this SQL: 
      
         select vc.conj_id  from words as w inner join verbs_conjs as vc on w.id=vc.word_id inner join conjs on conjs.id=vc.conj_id where w.word='rechnen';
      */
      
      $fetch = $this->get_table('FetchConjugation');

      $row = $fetch($wrface->main_verb()); 

      $conj_id = $row['conj_id'];
      
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
      // Pass the inserter to the word, noun or verb object, which will then
      // pass itself (this) to the appropriate Database insertion method.
      $wdResultFace->accept($this->inserter); 

      return true;
   } 

   function fetch_db_word($word) : \Iterator | false
   {
      $fetch = $this->get_table('FetchWord'); 
      
      $row = $fetch($word); 
      
      if ($row === false) return false; 

      $creator = new CreateDBWordResultIterator($this->pdo, $row);

      return $creator->getIterator(); 
   }
   
   function fetch_samples(int $word_id) : \Traversable | false
   {
       // Retrieve all the samples, if any, from the word definition in $wrface
       $fetch = $this->get_table('FetchSamples');
      
       return $fetch($word_id);
   }

   function save_samples(string $word, TranslateInterface $translator, \Traversable $sentences_iter) : bool
   {
      $samplesTbl = $this->get_table('SamplesTable'); 
   
      $prim_key = $this->word_prim_keys[$word];

      foreach ($sentences_iter as $sentence)  {

           // Will this exceed the rate limit 
          if ($this->$rate_limit($text) == false) {

           $msg =  "Azure hourly character limit will be exceeded. Waiting...." . self::$rate_limit->wait_time() . "\n";

           $this->log($msg);

            echo $msg;

            $this->rate_limit->wait(); // Do we want to wait?
         }

         $trans = $translator->translate($sentence, 'en', 'de'); 
         
         $samplesTbl->insert($sentence, $trans, $prim_key);                  
      }
      
      return true;
   }
}
