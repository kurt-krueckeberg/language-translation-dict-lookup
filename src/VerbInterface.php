<?php
declare(strict_types=1);
namespace Vocab;

interface VerbInterface extends WordInterface {

  public function conjugation() : string;
}
