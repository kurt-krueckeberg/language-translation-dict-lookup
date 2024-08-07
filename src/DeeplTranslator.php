<?php
declare(strict_types=1);
namespace Vocab;

class DeeplTranslator implements TranslateInterface {

    const http_429_too_many_requests = 429;
    const http_456_quote_exceeded = 456;

    private \DeepL\Translator $translator;

    function __construct(Config $c)
    {
       $apikey = $c->config['providers']['deepl']['apikey'];

       $this->translator = new \DeepL\Translator($apikey);
    }

/*
Deepl documentation on Error Handling:

Errors are indicated by standard HTTP status codes. It is important to make sure that your application
handles errors in an appropriate way. To that end, please consult the list of expected status code results
that is provided with each endpoint's documentation.

* HTTP 429: too many requests. Your application should be configured to resend the request after some delay,
  rather than constantly resending the request.

* HTTP 456: quota exceeded. The translation limit of your account has been reached. Consider upgrading your
  subscription.

* HTTP 500 and higher: temporary errors in the DeepL service. Your application should be configured to resend
  the request after some delay, rather than constantly resending the request.
 */
    function translate(string $text, string $dest, string $src="de") : string 
    {
       if ($dest == "en") {
           
           $dest = "en-US";
       }
           
       $dest = \strtoupper($dest);

       $src = \strtoupper($src);
  
       try {
       
           $result =  $this->translator->translateText($text, $src, $dest, ['formatlity' => 'prefer_less']);

       }  catch (ClientException $e) {

            $response = $e->getResponse();

            $statusCode = $response->getStatusCode();

            $errorMessage = $response->getMessage(); 

            if (http_429_too_many_requests == $statusCode) {

               // Delay for a period of time and, then, resend the translation request.
               throw new DelayRetryException("Too many requests. Delay and retry.");

            } /* elseif (http_456_quote_exceeded == $statusCode) {

                // print out message
                // rethrow exception
            
                match ($statusCode) {
               
                    echo Psr7\Message::toString($e->getResponse());
                    throw $e;
                };
              */  
            else {

                throw $e;
            }
            
     }  catch (\Exception $e) {

         return $result->text; 
     }
  } 
}
