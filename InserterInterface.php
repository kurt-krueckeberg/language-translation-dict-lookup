<?php
declare(strict_types=1);
namespace Vocab;

interface InserterInterface {

   function insert_word(WordInterface $wrface) : int;
   function insert_verb(WordInterface $wrface) : int;
   function insert_related_verb(WordInterface $wrface) : int;
   function insert_noun(WordInterface $wrface) : int;
}
