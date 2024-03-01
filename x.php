<?php
declare(strict_types=1);

use Vocab\{AzureTranslator, SystranTranslator, Config};

include 'vendor/autoload.php';

$c = new Config();

$arr = [ "Der Nachfrage nach Häusern in Krefeld will der Gladbacher Immobilienmakler aber jetzt nachkommen.",
"Inwieweit man den Griechen entgegenkommen wird, ist unklar"];

$azure = new AzureTranslator($c);

try {

  foreach($arr as $text) {
    $en =  $azure->translate($text, "en", "de");

    echo "Azure translation of:\n$text.\nis:\n";

    echo "$en.\n";
   }

} catch (\Exception $e) {

  echo $e->getMessage();
  echo "The code is: " . $e->getCode() . "\n";
}
