<?php
declare(strict_types=1);
namespace Vocab;

class DeeplTranslator extends \DeepL\Translator implements TranslatorInterface {

    function __construct(??)
    {

    }

    function translate(string $text, string $src, string $dest) : use the TranslatorInterface return type here.
    {
       $result =  $translator->translateText($text, $src, $dest, ['formatlity' => 'less']);

       return $result->text; 
    }
}
