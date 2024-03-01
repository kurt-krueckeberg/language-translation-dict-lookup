<?php
declare(strict_types=1);

use Vocab\{AzureTranslator, SystranTranslator, Config};

include 'vendor/autoload.php';

$c = new Config();

$text = "Der Nachfrage nach Häusern in Krefeld will der Gladbacher Immobilienmakler aber jetzt nachkommen.";

$azure = new AzureTranslator($c);
$sys = new SystranTranslator($c);

try {

  $en = $sys->translate($text, "en", "de");

  echo "The translation of: $text.\n";

  echo "By Systran:\n.$en.\n";

} catch (\Exception $e) {

  echo $e->getMessage();
  echo "The code is: " . $e->getCode() . "\n";
}
