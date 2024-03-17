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

       $params = $c->get_config($id);
       
       $this->client = new Client( ['base_uri' => $params['base_uri']]);

       $this->headers =  $params['headers'];
   }

   protected function request(string $method, string $route, array $options = array()) : string
   {
       $options['headers'] = $this->headers;

       $options['http_errors'] = $this->http_errors;
 
       $response = $this->client->request($method, $route, $options);
       
       return $response->getBody()->getContents();
   }
}
