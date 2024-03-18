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

    function translate(string $text, string $src, string $dest) : string 
    {
       $result =  $translator->translateText($text, $src, $dest, ['formatlity' => 'less']);

       return $result->text; 
    }
}
