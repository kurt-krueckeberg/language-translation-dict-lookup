<?php
declare(strict_types=1);
namespace Vocab;

abstract class AbstractDictionaryResultsIterator implements \Iterator {

    protected \ArrayIterator $iter;

    abstract protected function get_current(mixed $match) : WordResultInterface | false;

    public function __construct(array $arr)
    {
        $this->iter = new \ArrayIterator($arr);
    }

    function current() : WordResultInterface | false
    {
        $current = $this->iter->current(); 

        return ($current === false) ? $current : $this->get_current($current);
    }

    protected function seek(int $i)
    {
       $this->iter->seek($i);  
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
