<?php

$users = [
    [
        'id' => 1,
        'username' => 'john_doe',
        'email' => 'john.doe@example.com',
    ],
    [
        'id' => 2,
        'username' => 'jane_smith',
        'email' => 'jane.smith@example.com',
    ],
    [
        'id' => 3,
        'username' => 'alice_jones',
        'email' => 'alice.jones@example.com',
    ],
];

function exampleGeneratorMultiKey(array $input = []): Iterator
{
    foreach ($input as $key => $value) {

         echo "key = $key and value =\n";

         print_r($value); 


         echo "value['id'] = " . $value['id'] . "\n";

        yield $value['id'] => $value;
    }
}

foreach (exampleGeneratorMultiKey($users) as $key => $value) {
  //  echo "Current key: {$key}, and value: {$value['username']}". PHP_EOL;
  echo "Current key: {$key}, and value:\n";
  print_r($value);
}
