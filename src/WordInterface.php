<?php
declare(strict_types=1);
namespace Vocab;

interface WordInterface {
    
   function word_defined() : string;

   function  get_pos() : Pos;

   function definitions() : AbstractDefinitionsIterator;
}
