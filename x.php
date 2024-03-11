<?php
declare(strict_types=1);
use \SplFileObject as File;

use Vocab\{FileReader, Config, AzureTranslate};

include 'vendor/autoload.php';

$c = new Config();

$az = new AzureTranslate($c);

