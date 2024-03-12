<?php
declare(strict_types=1);
namespace Vocab;

class AzureTranslate {

   static string $route = "/translate?api-version=3.0";

   private string $endpoint;
   
   private string $subscription_key;
   
   private string $subscription_region;

   private function create_guid() : string
   {
     return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
         mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
         mt_rand( 0, 0xffff ),
         mt_rand( 0, 0x0fff ) | 0x4000,
         mt_rand( 0, 0x3fff ) | 0x8000,
         mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
     );
   }

   public function __construct(Config $c)
   {
      $provider_name = 'azure';

      $config = $c->config['providers'][$provider_name];

      $this->endpoint = $config['endpoint'];

      $this->subscription_key = $config['header']['Ocp-Apim-Subscription-Key'];       
      $this->subscription_region = $config['header']['Ocp-Apim-Subscription-Region'];       
   }
   
   function translate (string $text, string $dest_lang='en')
   {
       $requestBody = array (
          array (
            'Text' => $text,
          ),
       );

       $content = json_encode($requestBody);

       $headers = "Content-type: application/json\r\n" .
           "Content-length: " . strlen($text) . "\r\n" .
           "Ocp-Apim-Subscription-Key: $this->subscription_key\r\n" .
           "Ocp-Apim-Subscription-Region: $this->subscription_region\r\n" .
           "X-ClientTraceId: " . $this->create_guid() . "\r\n";

       // NOTE: Use the key 'http' even if you are making an HTTPS request. See:
       // http://php.net/manual/en/function.stream-context-create.php
       $options = array (
           'http' => array (
               'header' => $headers,
               'method' => 'POST',
               'content' => $content
           )
       );
 
       $params = "&to=" . $dest_lang;

       $context  = stream_context_create ($options);
 
       $file = $this->endpoint . self::$route . $params;
       
       $result = file_get_contents ($file, false, $context);
 
       return $result;
  }
}
