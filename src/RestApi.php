<?php
declare(strict_types=1);
namespace Vocab;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

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

       //$options[] = ['http_errors' => false]; 

       $options['http_errors'] = false;
 
       $response = $this->client->request($method, $route, $options);
       
       $code = $response->getStatusCode();
       
       $reason = $response->getReasonPhrase();
       
       if ($code != 200)

       return $response->getBody()->getContents();
   }
}
