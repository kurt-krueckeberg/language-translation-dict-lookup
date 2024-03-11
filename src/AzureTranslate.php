<?php
declare(strict_types=1);
namespace Vocab;

class AzureTranslate implements TranslateInterface {

    private string $base_uri;

    private string $headers;

    private function com_create_guid() 
    {
      return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
          mt_rand( 0, 0xffff ),
          mt_rand( 0, 0x0fff ) | 0x4000,
          mt_rand( 0, 0x3fff ) | 0x8000,
          mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
      );
    }

    function __construct(Config $c)
    {
       $config = $c->get_config(ProviderID::Azure);

       $this->base_uri = $config['base_uri']; 

       $this->key_and_region = '';

       $this->headers = "Content-type: application/json\r\n";
 
       foreach($config['headers'] as $key => $value) {

           $this->headers .= "$key: $value\r\n";
       }
    }

    //--function translate (string $key, string $text, string $dest_lang, string $src_lang='de') : string
    function translate (string $text, string $dest_lang, string $src_lang='de') : string
    {
       static $route = '/translate?api-version=3.0';

       $requestBody = [
           [ 'Text' => $text ]
       ];
     
       $content = json_encode($requestBody);

       // Append to headers the content length and the trace id.
       $this->headers .= "Content-length: " . strlen($content) . "\r\n" .
           "X-ClientTraceId: " . $this->com_create_guid() . "\r\n";

       $options = array (
           'http' => array (
               'header' => $this->headers,
               'method' => 'POST',
               'content' => $content
           )
       );
   
       $context  = stream_context_create ($options);

       $params = "&from" . $src_lang . "&to=" . $dest_lang;
   
       $result = file_get_contents ($this->base_uri . $route . $params, false, $context);

       $obj = json_decode($result);

       $text = trim($obj[0]->translations[0]->text, '"'); 

       return $text;
    }
}
