<?php
declare(strict_types=1);
namespace Vocab;


class LeipzigSentenceFetcher extends RestApi implements SentenceFetchInterface {

   private static $method = 'GET';
 
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
   
   public function fetch(string $word, int $count=3) : \Iterator | false
   {

      $route = urlencode($word);

      $contents = $this->request(self::$method, $route, ['query' => ['offset' => 0, 'limit' => $count]]);

      if ($contents === false)
          return $contents;

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
