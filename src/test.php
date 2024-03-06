<?php
$array1 = array("color" => "red", 2, 4);
$array2 = array("a", "b", "color" => "green", "shape" => "trapezoid", 4);

echo "First array is:\n";

print_r($array1);

echo "2nd array is:\n";
print_r($array2);

$result = array_merge($array1, $array2);

echo "Result of array_merge()\n";

print_r($result);

echo "Output of first array\n";

print_r($array1);
