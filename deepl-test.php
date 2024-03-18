<?php
declare(strict_types=1);
namespace Vocab;

use \SplFileObject as File;
use Vocab\{FileReader, Config, DeeplTranslator};

include 'vendor/autoload.php';

$samples = array("Aber das selbe gilt ja auch für Abführmittel.",
"Als man ihnen die Essensrationen reduzierte, gab man ihnen zusätzlich noch Abführmittel in den Kaffee, um das Hungergefühl zu verstärken.",
"Auch in Deutschland machen Pharmakonzerne mit Abnehm-Hilfen ein dickes Geschäft: Ungefähr knapp 140 Millionen Euro brachten Abführmittel, Appetitzügler und ähnliche frei verkäufliche Schlankheitsmittel den Firmen im Jahr 2010 ein.",
"Böse Absicht des Brasilianers war es sicherlich nicht, aber auf jeden Fall höchst fahrlässig.",
"V. hat nicht die Absicht, einen terroristischen Anschlag zu verüben.",
"Wieser vermutet hinter dem Jammern der Bauern auch noch die Absicht, den Preis hoch zu halten.",
"Israel ist am Montag nicht von seiner Absicht zum Siedlungsbau in einem kritischen Gebiet abgerückt.",
"Einige der Islamisten hätten Gegenstände dabei gehabt, die sie möglicherweise nicht in friedlicher Absicht mit sich geführt hätten, sagte ein Polizeisprecher.",
"Leider kein Albtraum aus dem man erwachen kann … In dem dritten Traum war da glaube ich mein verstorbener Kater.",
"«Aus dem Traum wird ein Albtraum.",
"Und bevor Jim sich fragt, wie die virtuelle Kayla an seine reale Nummer gelangt, fragt er sich, wie er aus diesem Albtraum wieder herauskommt.",
"Die jetzige Situation ist ein Albtraum.",
"Ein Albtraum für mich, dass die Parteien sich so angenähert haben und so ängstlich geworden sind, dass auch die Meinungsfreiheit bedenklich abnimmt.",
"Daran, dass ich einen Anfall erleiden könnte, dachte ich nie.",
"Andere Kabinettsmitglieder hatten zuvor berichtet, dass Bryson am Steuer einen Anfall erlitten habe und es dabei zu drei Zusammenstößen gekommen sei.",
"Das Gericht muss klären, ob der Unfall tatsächlich durch einen epileptischen Anfall ausgelöst wurde, und ob der Angeklagte von einem möglichen Anfall hätte wissen müssen.",
"Das Gericht muss klären, ob der Unfall tatsächlich durch einen epileptischen Anfall ausgelöst wurde, und ob der Angeklagte von einem möglichen Anfall hätte wissen müssen.",
"Eines Tages hat Anna einen Anfall - der Beginn einer Bewährungsprobe für die Liebe der Beiden.",
"Den kostenlosen Product Key gibt es unter Angabe der E-Mail-Adresse.",
"Diese Angabe stammte aus einem vertraulichen Papier.",
"Auch die Angabe einer Garage oder der Besitz eines Eigenheims kann die Beiträge drücken.",
"Zitate sind unter Angabe der Quelle zum Abdruck frei.",
"Höchste Vorsicht ist geboten, wenn man zur Angabe persönlicher Daten aufgefordert wird.",
"Unzählige Bauprojekte gab es seit der Gründung, derzeit bekommen die Eisbären eine neue Anlage.",
"Ist Ihr Bewerbungsschreiben unterschrieben und mit einem Hinweis auf die Anlage versehen?"
);
  
$c = new Config();
         
if (!file_exists($c->lookup_file())) {
     
    die($c->lookup_file() . " not found.\n");
}

 $words = $c->fetch_words();

 $trans = new DeeplTranslator($c);

 try {

   foreach ($samples as $sample) {

      $translation = $trans->translate($sample, "en", "de");
 
      echo "Input: $sample\n";
      echo "Input: $translation\n";
   }     
 
 }  catch (\Exception $e) {

   echo $e->getMessage();

   echo "-----\ngetting Trace as String: \n";
   echo $e->getTraceAsString();
 }
