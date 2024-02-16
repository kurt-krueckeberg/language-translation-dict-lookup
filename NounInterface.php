<?php
declare(strict_types=1);
namespace Vocab;

interface NounInterface extends WordInterface {
  public function gender() : Gender;
  public function plural() : string;
}
