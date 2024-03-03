<?php

class AzureTranslate {

   static $route = "/translate?api-version=3.0";

   private string $endpoint;
   private string $subscription_key;
   private string $subscription_region;

   function __construct(Config &c)
   {
      $provider_name = $id->get_provider();

      $config = $c->config['providers'][$provider_name];

      $this->endpoint = config['endpoint'];

      $this->subcription_key = $config['header'][0];       // subscription key

      $this->subscription_region = $config['header'][1];   // subscription region
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
           "X-ClientTraceId: " . \com_create_guid() . "\r\n";

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
 
       $result = file_get_contents ($this->endpoint . self::$route . $params, false, $context);
 
       return $result;
  }
}

