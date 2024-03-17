<?php
declare(strict_types=1);
namespace Vocab;

use GuzzleHttp\{Client, Psr7, Exception\ClientException};


class RestApi {

   protected Client $client;  

   private $headers = array();

   private bool $http_errors;

   public function __construct(Config $c, ProviderID $id, bool $http_errors=true)
   {      
       $params = $c->get_config($id);
       
       $this->client = new Client( ['base_uri' => $params['base_uri']]);

       $this->options['headers'] =  $params['headers'];

       $this->http_errors = $http_errors;
   }

   protected function request(string $method, string $route, array $options = array()) : string 
   {
      $options['headers'] = $this->headers;

      $options['http_errors'] = $this->http_errors; // Set to false to turn off Guzzle throwing of exceptions.

      try {

          $response = $this->client->request($method, $route, $options);
          
          $code = $response->getStatusCode();
          
          $reason = $response->getReasonPhrase();
          
       } catch (ClientException $e) {

            echo "Guzzle request encountered a 400 or 500 http error.\n";

            $d1 = $e->getRequest();
            
            $d2 = $e->getResponse();

            echo Psr7\Message::toString($e->getRequest());
            echo Psr7\Message::toString($e->getResponse());

            throw $e;
       }

       return $response->getBody()->getContents();
   }
}
