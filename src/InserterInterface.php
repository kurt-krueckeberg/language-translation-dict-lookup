<?php
declare(strict_types=1);
namespace Vocab;

interface InserterInterface {

   function insert_word(WordResultInterface $wrface) : int;
   function insert_verb(WordResultInterface $wrface) : int;
   function insert_related_verb(WordResultInterface $wrface) : int;
   function insert_noun(WordResultInterface $wrface) : int;
}
