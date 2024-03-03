<?php

// NOTE: Be sure to uncomment the following line in your php.ini file.
// ;extension=php_openssl.dll
// You might need to set the full path, for example:
// extension="C:\Program Files\Php\ext\php_openssl.dll"

if (!getenv("TRANSLATOR_TEXT_SUBSCRIPTION_KEY")) {
    throw new Exception ("Please set/export the following environment variable: TRANSLATOR_TEXT_SUBSCRIPTION_KEY");
} else {
    $subscription_key = getenv("TRANSLATOR_TEXT_SUBSCRIPTION_KEY");
}
if (!getenv("TRANSLATOR_TEXT_ENDPOINT")) {
    throw new Exception ("Please set/export the following environment variable: TRANSLATOR_TEXT_ENDPOINT");
} else {
    $endpoint = getenv("TRANSLATOR_TEXT_ENDPOINT");
}

class AzureTranslate {

   static $path = "/translate?api-version=3.0";

   function translate (string $text, string $host, string $path, string $key, string $dest_lang='en')
   {
       $requestBody = array (
          array (
            'Text' => $text,
          ),
       );

       $content = json_encode($requestBody);

       $headers = "Content-type: application/json\r\n" .
           "Content-length: " . strlen($content) . "\r\n" .
           "Ocp-Apim-Subscription-Key: $key\r\n" .
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
 
       $result = file_get_contents ($host . $path . $params, false, $context);
 
       return $result;
  }
}

