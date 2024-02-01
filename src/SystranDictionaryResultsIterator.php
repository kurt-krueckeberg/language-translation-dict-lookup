<?php
declare(strict_types=1);
namespace Vocab;

// More recent version
class SystranDictionaryResultsIterator extends AbstractDictionaryResultsIterator implements \Countable { 
    
    private bool $is_verb_family;
    
    public function __construct(string $word_lookedup, array $matches, \Collator $collator) 
    {
       $this->is_verb_family = false; // We start by assuming it is false.
        
       if (count($matches) == 1) {
           
           parent::__construct($matches);       
           return; 
       }  

       // Remainder of code is to determine if we have a family of prefix vebs (and one main verb),
       // To determine main_verb_index, first sort the array using German collation sequence
       $cmp = function (array $left, array $right) use($collator) { 

           return $collator->compare($left['source']['lemma'], $right['source']['lemma']);
       };

       usort($matches, $cmp);
       
       $main_verb_index = binary_search::find($matches, $word_lookedup, function(array $left, string $key) use($collator) { 
                     
           return $collator->compare($left['source']['lemma'], $key);
       });

       /*
         Next, determine whether we have a prefix-verbs family result or individual results.
        */
       $this->is_verb_family = $this->isPrefixVerbFamily($matches, $main_verb_index, $word_lookedup);
       
       if ($this->is_verb_family) {       

           $matches = $this->merge_verbs($matches);           
 
           $lookedup_index = binary_search::find($matches, $word_lookedup, function(array $left, string $key) use($collator) { //($GermanCollator)
                     
                                          return $collator->compare($left['source']['lemma'], $key);
                                         });
       
           // Set this iterator class to the end of results, to one beyond last element.
           $single_match = new SystranVerbFamilyResult($matches, $lookedup_index);
           
           parent::__construct(array($single_match));
       } 
    }

    function count() : int
    {
        return $this->iter->count();
    }
 
    /*
     Determine if the verb looked up, whose index in $matches is $this->lookedup_index, has other related verbs:
      1. count($matches) > 1 && word looked up is a verb. If so, is the verb looked up...

      2. contained in the other match results? We only need check one other match.

         If the loooked up result was $matches[0], we compare:  

         $pos = strpos($matches[1], $matches[0]) 

         If the verb looked up's results are not in $matches[0], we compare:

         $pops strpos($matches[0], $matches[$lookedup_index])
     
         Finally, we check that $pos !== false AND $pos !== 0.  
    */ 
    private function isPrefixVerbFamily(array $matches, int $lookedup_index, string $word_lookedup) : bool
    {  
      if (count($matches) <= 1 || $matches[$lookedup_index]['source']['pos'] !== 'verb') 
          
                return false;

      $index = ($lookedup_index == 0) ? 1 : 0; 
      
      $pos = strpos($matches[$index]['source']['lemma'], $word_lookedup);

      return (bool) ($pos !== false && $pos != 0);
   } 
 
   /* 
    * Merge the definitions for verbs like an~kommen (with a ~) with ankommen (without the ~) that has the conjugated tenses,
    * the remove the ~ verb version.
    * Not all prefix verb version have a non-tilde version, but for those that do, we as above.
    */
    private function merge_verbs($matches) : array
    {
       $results = [];
       
       for ($i = 0; $i < count($matches); ++$i) { 

           // Look for tilde-separated verbs.
           $pos = strpos($matches[$i]['source']['lemma'], "~");

           // If none found, save the prefix verb in results[]. 
           if ($pos === false) { 
               
               $results[] = $matches[$i];
               continue;
           }
          
           // We found one, look is next verb is non-tild version  
           $j = $i + 1;   
               
           $same = strcmp(substr($matches[$i]['source']['lemma'], 0, $pos), substr($matches[$j]['source']['lemma'], 0, $pos)); 
          
           // If none found, save the tilde version in results[] 
           if ($same != 0) {
               
               $results[] = $matches[$i];

           } else {

              // Create a new array with the merged definitions that adds any tilde-verb defintions not in the non-tilde version.
              $combined_definitions = $this->combine_definitions($matches[$j]['targets'], $matches[$i]['targets']);
              
              // Overwrite the non-tilde array of definitions (in ['targets']
              $matches[$j]['targets'] = $combined_definitions;
              
              // Save the non-tilde verb now with the merged definitions in results[]
              $results[] = $matches[$j]; 
              
              // Adjust $i, so we skip the non-tilde version
              $i = $j;
           }           
       }       

       return $results;
    }
 
    /*
     * Adds any definitions in $tilde['targets'] to the $nontilde['targets'] that are not present.
     */   
    private function combine_definitions(array $defns_nontilde, array $defns_tilde) : array
    {
       $cmp = function(array $left, array $right) : int { return strcmp($left['lemma'], $right['lemma']); };         
 
       usort($defns_tilde, $cmp); // sort by 'lemma' value
 
       usort($defns_nontilde, $cmp); // sort by 'lemma' value
 
       $bs = new binary_search();
 
       $additional_defns = [];
 
       for ($i = 0; $i < count($defns_tilde); ++$i) {
 
           $rc = $bs($defns_nontilde, $defns_tilde[$i]['lemma'], function(array $target, string $key) : int {
               
                 $rc = strcmp($target['lemma'], $key);
                 
                 return $rc;
                 });
 
           if ($rc === -1)
              $additional_defns[] = $defns_tilde[$i];
       }
       
       // Append the targets -- the definitions -- in the tilde array not found in the non-tilde array
       $all_definitions = array_merge($defns_nontilde, $additional_defns);
       
       return $all_definitions; 
    }
 
   protected function get_current(mixed $current) : WordResultInterface | false
   {
      if ($this->is_verb_family == false)

         return SystranWordResult::create($current); 

      else {
          
         return $current;
      }
   }
}
