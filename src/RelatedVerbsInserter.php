<?php
declare(strict_types=1);
namespace Vocab;

class RelatedVerbsInserter {

  private $insertVerb;

  private int $main_id; // non-prefix verb's words.id.
  private $related_ids = array(); // The words.id of the related prefix versions of the main verb word.id.
  
  private RelatedVerbsTable $relatedVerbsTable;
 
  /*
    TODO: Redo?
   */ 
  public function __construct(\PDO $pdo, callable $insertVerbFunctor, DictionaryResultIterator $iter)
  {
     $this->updated_prefixTbl = false;

     $this->relatedVerbsTable = new RelatedVerbsTable($pdo);
  
     $this->insertVerb = $insertVerbFunctor;

     $index = $iter->lookedup_index;

     $this->main_id = ($this->insertVerb)($iter[$index]);
     
     $iter->offsetUnset($index);
  }
 
  /*
     Overloaded function call operator method __invoke().
     The ctor insert the main verb--the non prefix version--into the tables: word, words_data and verbs_data.
     It then removed the main verb from the LookupResultIteror. This method is called when the remaining
     prefix versions are stored in the database. It calls back to the Database::insert_verb() method.
   */ 
  public function __invoke(WordDefinitionsInterface $deface) : int
  {
      $id = ($this->insertVerb)($deface);
      
      $this->related_ids[] = $id;
      
      return $id;  
  }

  public function updatePrefixTable()
  {
     if ($this->updated_prefixTbl == true) return;

     foreach($this->prefix_ids as $id) 

          $this->relatedVerbsTable->insert($this->main_id, $id);


     $this->updated_prefixTbl = true;
  }

  public function __delete()
  {
     $this->save();
  }
}
