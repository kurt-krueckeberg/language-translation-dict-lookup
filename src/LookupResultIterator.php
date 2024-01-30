<?php
declare(strict_types=1);
namespace Vocab;

abstract class LookupResultIterator extends \ArrayIterator {
   
   public readonly int $lookedup_index; // index in $matches array of the word we were looking for.

   public function __construct(string $word, array $matches = array())
   {
      parent::__construct($matches);

      binary_search::find($matches, $word, function(array $left, string $right) {

                          return strcmp($left['source']['lemma'], $right);
                       });
   }

   abstract protected function get_result(mixed $current) : WordDefinitionsInterface;

   public function current() : WordDefinitionsInterface
   {
      $current = parent::current();

      return $this->get_result($current);       
   }
   
   public function offsetGet(mixed $key): WordDefinitionsInterface
   {
       $current = parent::offsetGet($key);
       
       return $this->get_result($current);
   }
}
