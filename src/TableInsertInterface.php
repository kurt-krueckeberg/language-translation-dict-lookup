<?php
declare(strict_types=1);
namespace Vocab;

interface TableInsertInterface { 

   function insert(WordResultInterface $wrface, int $id = -1) : int;
}
