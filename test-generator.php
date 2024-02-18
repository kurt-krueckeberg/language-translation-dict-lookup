<?php
declare(strict_types=1);


$matches = array("aaaaaaa", "bbbbbbbbbb", "cccccccccc", "dddddddddddddd");
$main_verb_index = 3;

function GeneratorTest(array $matches, int $main_verb_index)
{
  yield $matches[$main_verb_index];
  
  foreach($matches as $index => $current) {
  
      echo "index= $index\n";
     
     if ($index == $main_verb_index) {
        
        continue;
  
     } else {
        
        yield $current;
     }
  }
}
  

$iter = GeneratorTest($matches, 3);

foreach($iter as $key => $value) {

  echo "index = $key and value = $value.\n";
}
