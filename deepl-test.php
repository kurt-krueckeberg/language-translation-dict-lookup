<?php
declare(strict_types=1);
namespace Vocab;

use \SplFileObject as File;
use Vocab\{FileReader, Config, DeeplTranslator};

include 'vendor/autoload.php';
  
$c = new Config();
         
if (!file_exists($c->lookup_file())) {
     
    die($c->lookup_file() . " not found.\n");
}

 $words = $c->fetch_words();

 $trans = new DeeplTranslator($c);

 try {

 
 } catch (ClientException $e) {

            echo "Guzzle request encountered a 400 or 500 http error.\n";

            $d1 = $e->getRequest();
            
            $d2 = $e->getResponse();

            echo Psr7\Message::toString($e->getRequest());
            echo Psr7\Message::toString($e->getResponse());

 } catch (\Exception $e) {

   echo $e->getMessage();

   echo "-----\ngetting Trace as String: \n";
   echo $e->getTraceAsString();
 }
