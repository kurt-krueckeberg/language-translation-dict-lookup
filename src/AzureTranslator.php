<?php
declare(strict_types=1);
namespace Vocab;

class AzureTranslator extends RestApi implements TranslateInterface {

   private \SplFileObject $errorLog;

   private \Collator $collator;
   
   public function __construct(Config $c)
   {
      parent::__construct($c, ProviderID::Azure); 

      $this->collator = $c->getCollator(); 
   }

   /*
    *  NOTE: Systran requires the language codes to be lowercase.
    *  If the language is not utf-8, the default, then you must speciy the encoding using the 'options' parameter.
    */
   final public function translate(string $text, string $to="en", string $from="de") : string 
   {
       // route: /translate?api-version=3.0&from=en&to=fr&to=zu"
       static $azure = array('method' => "POST", 'route' => "/translate?api-version=3.0");

       //$query = array();
       
       if ($from !== '') 
           $query['from'] = strtolower($from);
       
       $query['to'] = strtolower($to);
       
       //$body['text'] = $text;

       $request = $this->client->createRequest('POST', '/translate?api-version=3.0');
       
       $query = $request->getQuery();
 
       $query['from'] = strtolower($from);
       
       $query['to'] = strtolower($to);

       $postBody = $request->getBody();

       // $postBody is an instance of GuzzleHttp\Post\PostBodyInterface
       $postBody->setField('text', $text);

       // Send the POST request
       $contents = $client->send($request);
       
       //$contents = $this->request($azure['method'], $azure['route'], ['query' => $query, 'body' => $body]); 

       $std = json_decode($contents);
       
       return $std->outputs[0]->output;       
   }

   private function logError(string $err) : void
   {
      if (!isset($this->errorLog)) $this->errorLog = new \SplFileObject("error.log", "w");

      $this->errorLog->fwrite($err);
   }
}
