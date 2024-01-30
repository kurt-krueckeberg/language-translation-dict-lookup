<?php
declare(strict_types=1);
namespace Vocab;

interface WordExistsInterface {
    public function word_exists(string $word) : bool;
}
