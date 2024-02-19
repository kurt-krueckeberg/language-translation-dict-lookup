<?php
declare(strict_types=1);
use \SplFileObject as File;

use Vocab\{FileReader, Config, Facade};

include 'vendor/autoload.php';
   
 $c = new Config();
         
 if (!file_exists($c->lookup_file())) {
     
     die($c->lookup_file() . " not found.\n");
 }
  
 $translator = new Vocab\AzureTranslator($c);

 $r = $translator->translate("Guten Abend!");

 echo $r . "\n";
