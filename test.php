<?php
declare(strict_types=1);
use \SplFileObject as File;
use Vocab\{AzureTranslator, SystranTranslator, Config};

include 'vendor/autoload.php';

try {
    
    $c = new Config();
    
    $azure = new AzureTranslator($c);

    $english = $azure->translate("Guten Tag.", 'en', 'de');

    echo $english . "\n";

} catch (Exception $e) {

      echo "Exception: message = " . $e->getMessage() . "\nError Code = " . $e->getCode() . "\n";
  }
