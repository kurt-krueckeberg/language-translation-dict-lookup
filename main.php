<?php
declare(strict_types=1);

use Vocab\{Config, Vocab};
use GuzzleHttp\{Psr7, Exception\ClientException};

include 'vendor/autoload.php';

function fetch_words(string $wordfile)
{
   $file = new \SplFileObject($wordfile, "r");
   
   $file->setFlags(SplFileObject::DROP_NEW_LINE | SplFileObject::READ_AHEAD |  SplFileObject::SKIP_EMPTY);
   
   foreach ($file as $word) {
      yield $word; 
   }
}

$config = (new Config())->settings;
         
if (!file_exists($config['lookup_file'])) {

   die($config['lookup_file'] . " not found.\n");
}

 $vocab = new Vocab($config);

 try {

 /*
  * RestApi now throws 400 and 500 http errors. Its request() method will print the meaning of these exceptions to stderr.
  * Based on what gets thrown, I can decide if I really have a coding error or I need a differenet Azure payment plan.
  * 
  * NOTE: I believe the iterator returns a DBWord, DBNoun or DBVerb. All derived from DBWordBase that
  * that has get_word_id()
  */

 foreach (fetch_words($config['lookup_file']) as $word) {
  
    $vocab->db_insert($word); 
 } 
 
 /*
 foreach($words as $word) {
     
  $iter = $word_in_db = $fac->fetch_db_word($word);
  
  foreach($iter as $key => $value)
       var_dump($value);
 }
 */
 
 $words_inserted = $fac->create_html($input_words, 'output'); // todo: Do I need a method for adding samples as well.
 
 $fac->display_log(); 
 
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
