<?php
declare(strict_types=1);
namespace Vocab;

class DeeplTranslator implements TranslateInterface {

    private \DeepL\Translator $translator;

    function __construct(Config $c)
    {
        $apikey = $c->config['providers']['deepl']['apikey'];

        $this->translator = new \DeepL\Translator($apikey);
    }

    function translate(string $text, string $dest, string $src="de") : string 
    {
       if ($dest == "en") {
           
           $dest = "en-US";
       }
           
       $dest = \strtoupper($dest);
       $src = \strtoupper($src);
  
       $result =  $this->translator->translateText($text, $src, $dest, ['formatlity' => 'prefer_less']);

       return $result->text; 
    }
}
