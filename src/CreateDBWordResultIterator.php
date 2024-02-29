<?php
declare(strict_types=1);
namespace Vocab;

class CreateDBWordResultIterator { 

    private bool $is_verb_family;
    private int  $main_verb_index;

    \PDOStatement $pdostmt;

    public static function VerbFamilyGenerator(array $row, int $main_verb_index) : \Iterator 
    {
       yield new DBVerb(???);
       
       foreach($matches as $index => $current) {          
          
          if ($index == $main_verb_index) {
             
             continue;
    
          } else {
             
             yield new DBRelatedVerbResult($current);
          }
       }
    }   

    public static function SingleWordResultGenerator(array $arr) : \Iterator
    {
        foreach ($arr as $key => $current) {

           yield match($current['pos']) {

             'noun' => new DBNoun($????),
             'verb' => new DBVerb($????),                ),
             default => new DBnWord($????)
           };
        }
    }
    
    public function __construct(\PDOStatement $stmt)
    {
       $this->pdostmt = $stmt;
    }

    public function __invoke(string $word_lookedup, array $matches, \Collator $collator) 
    {
       $this->is_verb_family = false; // We start by assuming it is false.

       $rows = $this->pdostmt->fetchAll();
        
       if ($this->pdostmt->rowCount() > 1) {
           
         // Return an iterator with more than one match if row count > 1. 
  
         // TODO: we need the POS 
         if (pos is verb) { // We assume this is a verb family. The main verb is in row 0. 
           
           if (/* Test if row[1] also has Pos of Verb? and do strpos($row[1]['word'], $row[0]['word']) != 0 or false */ {
    
               $result = CreateDBWordResultIterator::VerbFamilyGenerator($matches, $this->main_verb_index); 

           } else {

               $result = CreateDBWordResultIterator::SimpleDictionaryResultsGenerator($matches); 
           }    
       } else {
    
          $result = CreateDBWordResultIterator::SingleWordResultGenerator($matches);
       }

       return $result; 
     }
  }
}
