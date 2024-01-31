<?php
declare(strict_types=1);
namespace Vocab;

class LeipzigSentenceFetcher extends RestApi implements SentenceFetchInterface {

   private static $method = 'GET';
 
   public function __construct(Config $c)
   {       
      parent::__construct($c, ProviderID::Leipzig_de);    
   }
   
   public function fetch(string $word, int $count=3) : \Iterator //ResultsIterator
   {
      $route = urlencode($word);

      try {

         $contents = $this->request(self::$method, $route , ['query' => ['offset' => 0, 'limit' => $count]]);

      } catch (\Exception $e) {

          return new NullIterator();
      }   

      $obj = json_decode($contents);

     /*
       $obj contains:
       {
         "count": some_number_her,
         "sentences": [ // SentenceInfomration json object.
           {
             "id": "string",
             "sentence": "string",
             "source": {
               "date": "2022-04-13T12:40:23.904Z",
               "id": "string",
               "url": "string"
             }
           }
         ]
       }
       SentenceInformation is a 'stdClass' containing:   
           1. id  => string
           2. sentence => the actual string text of the sample sentence
           3. source => ["daate" => ..., "id" => string, "url" => string]
        */

      // The iterator returns the 'sentence' member (of the SentenceInformation objects).
      return new SentencesIterator( $obj->sentences, function ($x) { return $x->sentence; } ); 
   }
}
