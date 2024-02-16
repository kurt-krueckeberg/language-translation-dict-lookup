<?php
declare(strict_types=1);
namespace Vocab;

abstract class AbstractDefinitionsIterator implements \Iterator {

    protected \ArrayIterator $iter;

    abstract protected function get_current(array $target) : DefinitionInterface | false;

    public function __construct(array $arr)
    {
        $this->iter = new \ArrayIterator($arr);
    }

    function current() : DefinitionInterface | false
    {
      $ele =  $this->iter->current();

      return ($ele === false) ? $ele : $this->get_current($ele);
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
