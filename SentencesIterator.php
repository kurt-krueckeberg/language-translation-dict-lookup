<?php
declare(strict_types=1);
namespace Vocab;

class SentencesIterator extends \ArrayIterator {
    
   private $func;
   
   public function __construct(array $results, callable $func)
   {
      parent::__construct($results);

      $this->func = $func;
   }
    
   public function current() : string // | false?
   {
       return ($this->func)(parent::current());
   }
}

