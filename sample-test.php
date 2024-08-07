<?php
declare(strict_types=1);

use Vocab\{Config, SamplesOnlyHtmlBuilder, LeipzigSentenceFetcher};

include 'vendor/autoload.php';
         
if ($argc != 2) {
    die ("Enter sample words file.\n");
}

if (!file_exists($argv[1])) {
     
    die($argv[1] . " not found.\n");
}

$file = new \SplFileObject($argv[1], "r");

$c = new Config;

$builder = new SamplesOnlyHtmlBuilder($c, "output");

$builder->build_output($file);
