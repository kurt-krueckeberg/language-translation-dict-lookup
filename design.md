# TODOES

matches typically only has one element and `count($matches) == 1`. It has more than one element when it has reflexsive or prefix verbs or it has
other forms of a preposition; for example, for 'um' it would return definitfor 'um' it would return definitions for 'um' and 'darum''

Thus, if 

```php

echo "The number of words defined actually was " . count($matches) . "\n";

DictionaryInterface::lookup($word, $from, $to) : DictionaryResult

class DictionaryResults extends \ArraryIterator  { // wrapper for $matches.

    public function __construct(array $matches)
    {
        parent:__construct($matches);
    } 

    public function words_defined() : int
    {
       return $this->count();
    }
    
    public offsetGet(int $i)
    {
       $element = parent::offsetGet($i);

       return new WordDefinitionsIterator($element); // current is match[$n];  
    }
 
    public function current() : DefinitionsIterator
    {
       $element = parent::current();

       return new WordDefinitionsIterator($element); // current is match[$n];  
    }   
}

class WordDefinitionsIterator extends \ArrayIterator { // wrapper for match, $matches[$n] for some $n.
 
    public function __construct(array $a)
    {
        parent:__construct($a);
    } 

    public offsetGet(int $i)
    {
       $element = parent::offsetGet($i);

       return new WordDefinition($element); // current is match[$n];  
    }
 
    public function current() : DefinitionsIterator
    {
       $element = parent::current();

       return new WordDefinition($element); // current is match[$n];  
    }
}
```

Is `RecurisiveIteratorIterator()` of help? See:

* [How does RecursiveIteratorIterator work in PHP](https://stackoverflow.com/questions/12077177/how-does-recursiveiteratoriterator-work-in-php)
* [Recursive iteration of Multidimensional Array](https://gist.github.com/useless-stuff/dba7ea4705036f870895)
* [Iterating over Trees in PHP](https://evolvingweb.com/blog/iterating-over-trees-php)
* [Directory Iteration in PHP](https://gist.github.com/hakre/3599532)

Examples:

1.

```php
$data = array(
    'fruits' => array(
        'apple',
        'banana',
        'orange'
    ),
    'vegetables' => array(
        'carrot',
        'lettuce',
        'tomato'
    )
);

$iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($data));
```

2.  

```php
Examples

<?php
$array = array(
    array(
        array(
            array(
                'leaf-0-0-0-0',
                'leaf-0-0-0-1'
            ),
            'leaf-0-0-0'
        ),
        array(
            array(
                'leaf-0-1-0-0',
                'leaf-0-1-0-1'
            ),
            'leaf-0-1-0'
        ),
        'leaf-0-0'
    )
);

$iterator = new RecursiveIteratorIterator(
    new RecursiveArrayIterator($array),
    $mode
);
foreach ($iterator as $key => $leaf) {
    echo "$key => $leaf", PHP_EOL;
}
?>

Output with $mode = RecursiveIteratorIterator::LEAVES_ONLY

0 => leaf-0-0-0-0
1 => leaf-0-0-0-1
0 => leaf-0-0-0
0 => leaf-0-1-0-0
1 => leaf-0-1-0-1
0 => leaf-0-1-0
0 => leaf-0-0

Output with $mode = RecursiveIteratorIterator::SELF_FIRST

0 => Array
0 => Array
0 => Array
0 => leaf-0-0-0-0
1 => leaf-0-0-0-1
1 => leaf-0-0-0
1 => Array
0 => Array
0 => leaf-0-1-0-0
1 => leaf-0-1-0-1
1 => leaf-0-1-0
2 => leaf-0-0

Output with $mode = RecursiveIteratorIterator::CHILD_FIRST

0 => leaf-0-0-0-0
1 => leaf-0-0-0-1
0 => Array
1 => leaf-0-0-0
0 => Array
0 => leaf-0-1-0-0
1 => leaf-0-1-0-1
0 => Array
1 => leaf-0-1-0
1 => Array
2 => leaf-0-0
0 => Array
```

## Design

1. The definitions are not being saved to `word_defns` table. 

2.  WordDefinitionInterface returns all the definitions of a word not just a "current one". Thus WordTable::pos() only corresponds to a "current" 
definition, not the entire array--right?

3. When a verb has prefix versions, definitions for all of them are returned; i.e., the definitions of many verbs are returned in the matches array--correct?
Each of them has an array of definitions, each of which has associated expressions.

For these prefix verbs, is `SystranLookupResultIterator::get_current(mixed $current)` returning something that make sense?

4. Is the design of WordDefinitionInterface correct? Do we need to iterate of the definitions?

So definitions (and any associated , conjugatsionverbs exist and are also returned? 

Then each matches[] element corresponds to a different verb!!!

* Check the return codes from these methods and those they call for completeness and accuracty
* We need to add a method to PrefixVerbInsert to insert the records into `prefix_verbs`.

### Design for PrefixVerbInserter

We will save the primary key of the main verb(`word.id`) so we remeber it. Then we insert each of the other definitions in the LookupResultIterator, and we save each

new primary key of the word/verb insert into `words` in an array: `private $prefix_verbs_ids = array();`.

Then we will insert the pair `$main_verb_id`/`$prefix_verbs_ids[$i]` for each `$i`.

This will make the Database the mediator of the Table-related.

TODO: We also need to insert the expressions that accompany each definition. Add this to WordTable.

## Convert Configuration to PHP array

More work todo.

## The Systran definition(s) of a verb can be that of a noun


### Solution

See [Systran Lookup results](./design-systran-lookup-results.md).

See the `related_verbs` table. Change `SystranVerbInserter` to insert the related verbs into `related_verbs`. 


/*

Is this a prospective prefixverb? Check if:
* the first letter of the word being looked up is lowercase
* this first letters differs from the 1st letter of the word in $match['source']['lemma'] whose definition
  was returned (in the $match['targets'] array). This test is simplier than strstr($match['source']['lemma'], $match['srouce'['term')
* the word being defined $match['source'['lemma'] is a verb: $match['source']['pos'] == 'verb'

```php
      if (count($iter) > 0) {

        // test for prefix verbs or verb + reflexive
        $word_defined = $deface->word_defined();
  
        $word_lookedup = $deface->word_lookedup();
        
        $word_defined_pos = $deface->get_pos();
        
        $b1 = $word_defined_pos == 'verb'; // The first match is 
  
        $b2 = ctype_lower($word_lookedup[0]); // It is a prospective verb
        
        $b3 = $word_defined[0] != $word_lookedup[0];
        
        
        if ($b1 && $b2 && $b3) {
  
           new VerbTablesMediator(
  
        } else {
  
           echo "$word itself is not a prospective verb version.\n";
        }
     } else 

          new WordTableMediator($iter)
   } 
```
