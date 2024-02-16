<?php
declare(strict_types=1);
namespace Vocab;

abstract class AbstractSample implements \Iterator {

    protected array $matches;

    abstract protected function get_current(mixed $match) : string | null;

    public function __construct(array $matches)
    {
        $this->matches = $matches;
    }

    function current() : string | null
    {
        $current = \current($this->matches); 

        return (is_null($current)) ?  $current : $this->get_current($current);
    }

    function next() : void
    {
       \next($this->matches);
    }

    function valid() : bool
    {
       $ele = \current($this->matches);
       
       return ($ele !== false) ? true : false;           
    }

    function key() : int | null
    {
       return \key($this->matches);
    }

    function rewind() : void
    {
       \reset($this->matches);
    }
}
