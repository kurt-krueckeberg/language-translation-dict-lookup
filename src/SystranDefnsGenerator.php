<?php
declare(strict_types=1);
namespace Vocab;

/*
 * Have class that uses this implement \IteratorAggregate and have its getIterator() method do:
 * return SystranDefnsGenerator($targets)
 */
function SystranDefnsGenerator(array $match) : \Iterator
{
  foreach($match as $key => $value) {

      yield $value['lemma'] => $value['targets']['expressions'];
  }
}
