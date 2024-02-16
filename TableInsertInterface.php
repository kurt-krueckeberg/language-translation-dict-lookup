<?php
declare(strict_types=1);
namespace Vocab;

interface TableInsertInterface { 

   function insert(WordInterface $wrface, int $id = -1) : int;
}
