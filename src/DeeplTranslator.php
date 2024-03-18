<?php
declare(strict_types=1);
namespace Vocab;

class DeeplTranslator implements TranslatorInterface {

    \DeepL\Translator $translator;

    function __construct(Config $c)
    {
        $apikey = $c->config['providers']['deepl']['apikey'];

        $this->translator = new \DeepL\Translator($apikey);
    }

    function translate(string $text, string $src, string $dest) : use the TranslatorInterface return type here.
    {
       $result =  $translator->translateText($text, $src, $dest, ['formatlity' => 'less']);

       return $result->text; 
    }
}
