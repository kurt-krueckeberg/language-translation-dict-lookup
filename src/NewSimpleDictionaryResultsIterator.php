<?php
declare(strict_types=1);
namespace Vocab;

class NewSimpleDictionaryResultsIterator implements \Countable {

    public function count()
    {
        return count($this->array);
    }
    
    public static function SimpleDictionaryResultsIterator(array $arr) : \Iterator
    {
        foreach ($arr as $key => $current) {

           return match($current['source']['pos']) {

             'noun' => new SystranNoun($current),
             'verb' => new SystranVerb($current, function() use($current) : string { 
                    return $current['source']['inflection'];}
               ),
             default => new SystranWord($current)
           };
        }
    }
        
}
