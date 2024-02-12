<?php
declare(strict_types=1);
namespace Vocab;

interface RelatedVerbInterface extends WordInterface {

  public function get_main_verb() : string;
}
