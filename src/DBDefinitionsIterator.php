<?php
declare(strict_types=1);
namespace Vocab;

class DBDefinitionsIterator implements \Iterator {
       /*
          TODO
          Where is the pseudo code for this class?
          What is the precise meaning of each of these private class attributes? 
          1. What is their function?
          2. And are they named accordingly?
        */

       private $expr_cnts;
       private $rows;
       private $expr_offset;
       private $exprs_index;
       
       function __construct(array $rows, $expr_cnts)
       {
           $this->rows = $rows;
           $this->expr_cnts = $expr_cnts;
           $this->expr_offset = $this->exprs_index = 0; 
       }
       
       function rewind(): void 
       {
           \reset($this->rows);
       }
       
       function valid() : bool
       {
          // Check if current is false, which implies cursor is at end of array.
          return (\current($this->rows) === false) ? false : true;
       }

       function expressions() : array
       {
          return \array_slice(array_column($this->rows, 'expr'), $this->expr_offset, $this->exprs_cnt[$this->current_index]);
       }

       function next() : 
       {
          \next($this-> 
          $this->expr_offset += $this->exprs_cnt[$this->current_index]; 
       } 
       
       $a = [];

       $current = 0;

       foreach($this->expr_counts as $index => $cnt) {

           /* array_chunck
            * return: array_slice($this->rows['expr'], $current, $this->expr_counts[$index]);
            * 
            */
           $a[$this->defns[$key] = [array_splice($this->rows['expr'], $current, $cnt), array_splice($this->rows['translated_expr'], $current, $cnt);

           $current += $cnt;
       }
       return new ????($a);  
}
