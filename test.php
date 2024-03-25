<?php
declare(strict_types=1);
use \SplFileObject as File;

use Vocab\{FileReader, Config, Vocab};
use GuzzleHttp\{Psr7, Exception\ClientException};

include 'vendor/autoload.php';
  
$c = new Config();
         
if (!file_exists($c->lookup_file())) {
     
    die($c->lookup_file() . " not found.\n");
}

 $fac = new Vocab($c);

 $words = $c->fetch_words();

 try {

 /*
  RestApi now thwrows 400 and 500 http errors. Its request() method will print the meaning of these exceptions to stderr.
  Based on what gets thrown, I can decide if I really have a coding error or I need a differenet Azure payment plan.
  */

 $words_inserted = [ 'sich begeben',
'begeben',
'beheizen',
'belasten',
'aufbewahren',
'aufbewahren',
'bewahren',
'Ehre',
'ehren',
'Eintrag',
'Einweisung',
'Empörung',
'Macht',
'aufmachen',
'ausmachen',
'durchmachen',
'festmachen',
'freimachen',
'gleichmachen',
'klarmachen',
'machen',
'sich machen',
'mitmachen',
'nachmachen',
'weitermachen',
'wettmachen',
'abfahren',
'anfahren',
'ausfahren',
'einfahren',
'einfahren',
'fahren',
'festfahren',
'fortfahren',
'fortfahren',
'herausfahren',
'hinausfahren',
'hinunterfahren',
'hinfahren',
'mitfahren',
'vorbeifahren',
'weiterfahren',
'wiedereinfahren',
'zurückfahren'
];

 $fac->create_html($words_inserted, 'output');
 
 file_put_contents("words-inserted.txt", implode("\n", $words_inserted));

 } catch (ClientException $e) {

      echo "Guzzle request encountered a 400 or 500 http error response.\n";

      echo Psr7\Message::toString($e->getRequest());

      echo Psr7\Message::toString($e->getResponse());

 } catch (\Exception $e) {

      echo $e->getMessage();
  
      echo "-----\ngetting Trace as String: \n";

      echo $e->getTraceAsString();
 }
