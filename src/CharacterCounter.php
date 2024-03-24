<?php
declare(strict_types=1);
namespace Vocab;

class CharacterCounter implement \Countable {

  private int $cnt;

  function __construct()
  {
     $this->cnt = 0;
  }

  function count() : int
  {
     return $this->char_cnt;
  }

  function reset() : void
  {
     $this->cnt = 0; 
  }

  function __invoke(string $input)
  {
      $this->char += \strlen($input);  
  }
}


