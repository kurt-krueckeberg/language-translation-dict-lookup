<?php
declare(strict_types=1);
namespace Vocab;

interface RelatedVerbInterface extends WordInterface {

  public function main_verb() : string;
  public function main_word_id() : int
}
