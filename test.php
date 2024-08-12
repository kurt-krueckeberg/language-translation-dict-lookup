<?php
declare(strict_types=1);

use Vocab\{Config, Vocab, AzureTranslator, TranslateInterface, HtmlBuilderInterface};
use GuzzleHttp\{Psr7, Exception\ClientException};

include 'vendor/autoload.php';

class Tester {

        private TranslateInterface $trans;
        
        private HtmlBuilderInterface $builder;

        function __construct(array $config)
        {
            $this->trans = new AzureTranslator($config);
            $this->builder = new Bu
        }
 
	function __invoke(string $text)
	{
	  $trans = $this->trans->translate($text, "en", "de");
		
	  echo $trans . "\n";
		
	  \file_put_contents('bug-translation.txt', $trans);
	}
}

try {

$config = (new Config)->config;

$test = new Tester($config);
$texts = ['Wer nicht ganz so viel ausgeben will, kann eine kleine SSD nur für das Betriebssystem nutzen und Musik, Filme und Programme weiter auf einer herkömmlichen Festplatte aufbewahren.',
'Deshalb wird sich die Fed ihr letztes Pulver noch aufbewahren, vor allem weil wir uns mitten im Wahlkampf befinden und man dort schnell auf viel politischen Wiederstand treffen könnte.'];

foreach ($texts as $text) {
    
    $test($text);
}

 } catch (ClientException $e) {

      echo "Guzzle request encountered a 400 or 500 http error response.\n";

      echo Psr7\Message::toString($e->getRequest());

      echo Psr7\Message::toString($e->getResponse());

 } catch (\Exception $e) {

      echo $e->getMessage();
  
      echo "-----\ngetting Trace as String: \n";

      echo $e->getTraceAsString();
 }
