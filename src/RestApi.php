<?php
declare(strict_types=1);
namespace Vocab;

use GuzzleHttp\Client;

class RestApi {

   protected Client $client;  

   private $headers = array();
 
   public function __construct(Config $c, ProviderID $id)
   {      
       $params = $c->get_config($id);
       
       $this->client = new Client( ['base_uri' => $params['base_uri']]);

       $this->headers =  $params['headers'];
   }

   protected function request(string $method, string $route, array $options = array()) : string 
   {
      $options['headers'] = $this->headers;

      $options['http_errors'] = true; // Set to false to turn off Guzzle throwing of exceptions.

      try {

          $response = $this->client->request($method, $route, $options);
          
          $code = $response->getStatusCode();
          
          $reason = $response->getReasonPhrase();
          
       } catch (ClientException $e) {

            echo "Guzzle request encountered a 400 or 500 http error.\n";
            echo Psr7\Message::toString($e->getRequest());
            echo Psr7\Message::toString($e->getResponse());
            throw $e;
       }

       return $response->getBody()->getContents();
   }
}
