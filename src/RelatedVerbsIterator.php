<?php
declare(strict_types=1);
namespace Vocab;

class RelatedVerbsIterator implements \Iterator {

   private int $main_verb_index;
   private array $matches; 

   function __construct(array $matches, int $main_verb_index)
   { 
      $this->matches = $matches;

      $this->main_verb_index = $main_verb_index;
   }

   function current() : SystranVerbResult | false 
   {
      $current_index = key($this->matches);
      
      if ($current_index == $this->main_verb_index) {
             
           /*
             If the current_index is the non-prefix verb, advance to next verb in $matches, but if the non-prefix verb was
             the last element in $matches, return false.
            */
           next($this->matches);
           
           return $this->valid() ? $this->current() : false;
      }

     return new SystranVerbResult($this->matches[$current_index], function() use($current_index) : string {

         if (!empty($this->matches[$current_index]['source']['inflection']))  

            $conjugation = $this->matches[$current_index]['source']['inflection'];  

         else
 
            $conjugation = $this->matches[$this->main_verb_index]['source']['inflection'];

         return str_replace(array("(", "/", ")"), array( "", ", ", ""), $conjugation); 
     });
   }
   
   function next() : void
   {
      \next($this->matches);
   }
   
   function key() : mixed
   {
      return \key($this->matches);
   }

   function valid() : bool
   {
      $tmp = current($this->matches);
      
      return ($tmp === false) ? false : true;
   }

   function rewind() : void
   {
      \reset($this->matches);
   }
}
