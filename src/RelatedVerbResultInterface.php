<?php
declare(strict_types=1);
namespace Vocab;

interface RelatedVerbResultInterface extends WordResultInterface {

  public function get_main_verb() : string;
}
