<?php
declare(strict_types=1);
namespace Vocab;

/*
 * Generator for VerbFamilyIterator. The original code was in VerbFamilyIterator.php
 */
 
function GeneratorVerbFamil(array $matches, int $main_verb_index) : \Iterator 
{
   $conjugator = function () : string  {

       $conj = $this->matches[$this->main_verb_index]['source']['inflection'];

       return str_replace(array("(", "/", ")"), array( "", ", ", ""), $conj); 
   };

   foreach($matches as $index => $current) {
      
       if ($index == 0) { // This also works if index == main_verb_index
    
         yield new SystranVerb($matches[$main_verb_index], $conjugator);

      } else if ($index == $main_verb_index) {
         
         continue;

      } else {
         
         yield new SystranRelatedVerbResult($current);
      }
   }
}
