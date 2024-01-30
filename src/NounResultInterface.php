<?php
declare(strict_types=1);
namespace Vocab;

interface NounResultInterface extends WordResultInterface {
  public function gender() : Gender;
  public function plural() : string;
}
