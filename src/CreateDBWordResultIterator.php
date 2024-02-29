<?php
declare(strict_types=1);
namespace Vocab;

class CreateDBWordResultIterator { 

    private bool $is_verb_family;
    private int  $main_verb_index;

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
    
    public function __construct(Pos $pos, int $word_id)
    {
       $this->is_verb_family = false; // We start by assuming it is false.
 
       $rows = match ($pos) {
          
          Pos::Verb => $this->fetchVerb($this->word_id), 
          Pos::Noun => $this->fetchNoun($this->word_id),
          default =>  $this->fetchWord(????) // Only need definitions and expressioins.
         
       };
 
       if (count($rows) > 1) {
           
         // Return an iterator with more than one match if row count > 1. 
  
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
