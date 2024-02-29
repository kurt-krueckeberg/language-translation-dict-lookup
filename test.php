<?php
declare(strict_types=1);

use Vocab\{CreateDBWordResultIterator, Pos};

include 'vendor/autoload.php';

$x = new CreateDBWordResultIterator(Pos::Verb, 1);
