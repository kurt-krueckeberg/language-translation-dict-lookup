<?php
declare(strict_types=1);
namespace Vocab;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

class RestApi {

   protected Client $client;  

   private $headers = array();

   private bool $http_errors;

   public function __construct(Config $c, ProviderID $id, bool $http_errors=true)
   {      
       $this->http_errors = $http_errors;

       $config = $c->get_config($id);
       
       $this->client = new Client( ['base_uri' => $config['endpoint']]);
       
       $this->headers =  $config['header'];
   }

   protected function request(string $method, string $route, array $options = array()) : string
   {
       $options['headers'] = $this->headers;

       $options['http_errors'] = $this->http_errors;
 
       $response = $this->client->request($method, $route, $options);
       
       return $response->getBody()->getContents();
   }

/* This illustrates the use of the ClientException and 

use GuzzleHttp\{Psr7, Exception\ClientException}; // Goes at top of file.

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
            
            echo Psr7\Message::toString($e->getResponse());

            $response = $e->getResponse();

            echo "The response code was: " . $response->getStatusCode() . "\n"; 
            throw $e;
       }

       return $response->getBody()->getContents();
   }
*/
}
