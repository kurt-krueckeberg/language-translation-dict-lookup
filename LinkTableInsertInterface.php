<?php
declare(strict_types=1);
namespace Vocab;

interface LinkTableInsertInterface { 

   function insert(int $key_one, int $key_two) : int;
}
