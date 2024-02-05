<?php
declare(strict_types=1);
namespace Vocab;

class SimpleDictionaryResultsIterator implements \Iterator, \Countable {

    protected \ArrayIterator $iter;

    public function __construct(array $arr)
    {
        $this->iter = new \ArrayIterator($arr);
    }

    function count() : int
    {
       return $this->iter->count();
    }

    function current() : WordResultInterface | false
    {
        $current = $this->iter->current(); 

        if ($current === false) return $current;

        return match($current['source']['pos']) {

             'noun' => new SystranNounResult($current),
             'verb' => new SystranVerbResult($current, function() use($current) : string { 
                    return $current['source']['inflection'];}
               ),
             default => new SystranWordResult($current)
         };
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
