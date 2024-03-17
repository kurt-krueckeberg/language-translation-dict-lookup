<?php
declare(strict_types=1);
namespace Vocab;
use GuzzleHttp\{Psr7, Exception\ClientException};

class LeipzigSentenceFetcher extends RestApi implements SentenceFetchInterface {

   private static $method = 'GET';

   static int $bad_request = 404;
 
   public function __construct(Config $c)
   {       
      parent::__construct($c, ProviderID::Leipzig_de);    
   }

   static public function SentencesGenerator(array $sentences) : \Iterator
   {
      foreach($sentences as $object) {

         yield $object->sentence;
      }
   }
   
   public function fetch(string $word, int $count=3) : \Iterator
   {
      $route = urlencode($word);

      try {

          $contents = $this->request(self::$method, $route, ['query' => ['offset' => 0, 'limit' => $count]]);

      } catch (ClientException $e) {

        //echo Psr7\Message::toString($e->getResponse());

        $response = $e->getResponse();

        if ($response->getStatusCode() == self::$bad_request) {

           // Return null iterator
           return LeipzigSentenceFetcher::SentencesGenerator(array());

        } else {
           throw $e;
        }
     }

      $obj = json_decode($contents);

     /* $obj contains:
       {
         "count": some_number_her,
         "sentences": [ // Array of SentenceInfomration json objects.
           {
             "id": "string",
             "sentence": "string",  <---- The sample sentence
             "source": {
               "date": "2022-04-13T12:40:23.904Z",
               "id": "string",
               "url": "string"
             }
           }
         ]
       }
      */
      // The iterator returns each 'sentence' member of the SentenceInformation objects array.
      return LeipzigSentenceFetcher::SentencesGenerator( $obj->sentences );
      /*
      return new SentencesIterator( $obj->sentences, function ($x) {
              return $x->sentence; } 
          ); 
       */
   }
}
