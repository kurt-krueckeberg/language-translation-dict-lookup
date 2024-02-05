<?php
declare(strict_types=1);
namespace Vocab;

interface DictionaryInterface {
   
   public function lookup(string $str, string $src_lang, string $dest_lang, bool $exact_match=false) : \Iterator;
   public function getDictionaryLanguages() : array;
}
