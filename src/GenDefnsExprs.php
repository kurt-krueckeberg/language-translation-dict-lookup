<?php
/*
 * Example of Generator that returns a definition as the key and its expressions as values.
 *
 * I could use this a the \Iterator for DBWordBase and for the SystranWord/Verb/Noun, which would do likewise.
 */
function gen_defn_exps(array $defs, array $e, array $counts) : \Iterator
{
    $offset = 0;

    foreach ($defs as $index => $value) {

        echo "key = $index and value = $value\n";

        yield $value => array_slice($e, $offset, $counts[$index]);

        echo "offset before: $offset\n";

        $offset += $counts[$index];

        echo "offset after: $offset\n"; 
    }
}

$defns = ["def0", "def1", "def2"];

$expressions = ["def0-e0", "def0-e1", "def2-e0", "def2-e1"];

$counts = array(2,0,2);

foreach (gen_defn_exps($defns, $expressions, $counts) as $key => $value) {

  echo "Current key: {$key}, and value = \n";
  
  print_r($value);
  
  echo "\n";
}
