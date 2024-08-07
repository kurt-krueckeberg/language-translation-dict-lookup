<?php
declare(strict_types=1);

use Vocab\{Config, Vocab, AzureTranslator};
use GuzzleHttp\{Psr7, Exception\ClientException};

include 'vendor/autoload.php';

$config = (new Config)->config;

$translator = new AzureTranslator($config);

 try {

$text = 'Wer nicht ganz so viel ausgeben will, kann eine kleine SSD nur für das Betriebssystem nutzen und Musik, Filme und Programme weiter auf einer herkömmlichen Festplatte aufbewahren.';

$trans = $translator->translate($text, "en", "de");

echo $trans . "\n";

\file_put_contents('bug-translation.txt', $trans);


 } catch (ClientException $e) {

      echo "Guzzle request encountered a 400 or 500 http error response.\n";

      echo Psr7\Message::toString($e->getRequest());

      echo Psr7\Message::toString($e->getResponse());

 } catch (\Exception $e) {

      echo $e->getMessage();
  
      echo "-----\ngetting Trace as String: \n";

      echo $e->getTraceAsString();
 }
