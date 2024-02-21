 #!/usr/bin/env php
<?php
declare(strict_types=1);

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

include 'vendor/autoload.php';

  $x = new Client();

  $options['http_errors'] = false;

  $option['query'] = ['from' => 'de', 'to' => 'en', 'api-version' => '3.0'];

  $options['headers'] = ['Ocp-Apim-Subscription-Key' => '8fd23d4521904848acccec18e8d8ab18', 'Ocp-Apim-Subscription-Region' => 'eastus', 'Content-Type' => 'application/json'];

//  $encoded = json_encode('Guten Tag!');

  $options['json'] = ['text' => 'Guten Tag.'];
 
  //++$response = $x->request('POST', '/translate', $options);

  $response = $x->post('https://api.cognitive.microsofttranslator.com/translate', $options);

  $code = $response->getStatusCode();
  
  //$reason = $response->getReasonPhrase();
  
  if ($code != 200) {
      
     echo "Status code is: $code.\n";
     
  } else {

     echo "Success. Results:\n";
     var_dump($response); 

  }

 /* 
 $translator = new Vocab\AzureTranslator($c);

 $r = $translator->translate("Guten Abend!");
  */
