<?php
declare(strict_types=1);
namespace Vocab;

interface VerbResultInterface extends WordResultInterface {

  public function conjugation() : string;
}
