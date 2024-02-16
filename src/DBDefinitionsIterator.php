<?php
declare(strict_types=1);
namespace Vocab;
 
class DBDefinitionsIterator {
 
   function current() : DefinitionInterface | false
   {
     $ele =  $this->iter->current();

     if ($ele === false) return false;

     $index = $this->iter->key();

     $this->expressions_cnt[$index]; // todo: I also need both the offset from the start of the first element to the current set of exprssions
                                     // and the total number of expressions. 
     
     return new DBDefinition($this->iter[$index], array() ); // <-- TODO: 2nd parameter not yet right per comment above.
   }

   function next() : void
   {
      $this->iter->next();
   }

   function valid() : bool
   {
      return $this->iter->valid();
   }

   function key() : int | null
   {
      return $this->iter->key();
   }

   function rewind() : void
   {
      $this->iter->rewind();
   }
}
