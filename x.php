<?php
declare(strict_types=1);
use \SplFileObject as File;

use Vocab\{FileReader, Config, AzureTranslate};

include 'vendor/autoload.php';

$c = new Config();

$az = new AzureTranslate($c);

$a = ["Guten Tag!", "Guten Abend!"];

foreach($a as $str) {
    
    echo $az->translate($str);
    echo "\n";
}

