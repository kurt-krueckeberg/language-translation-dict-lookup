<?php
declare(strict_types=1);
use \SplFileObject as File;

use Vocab\{MessageLog, FileReader};

include 'vendor/autoload.php';
  
$file = new \SplFileObject("./test.log","w");

$log = new MessageLog($file);

$log->log("msg1");
$log->log("msg2");
$log->log("msg3");

$log->report();
