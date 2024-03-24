<?php
declare(strict_types=1);
namespace Vocab;

class RateLimitGauge  {

  private int $char_cnt; // Number of characters per hour.

  private int $hourly_limit; 

  private static $hour_seconds = 3600;

  function __construct(int $limit)
  {
     $this->char_cnt = 0;

     $this->hourly_limit = $limit;

     $this->time_start = \time();
  }

  function reset() : void
  {
     $this->char_cnt = 0; 
  }

  function willExceed(string $input) : bool
  {
     return (\strlen($input) + $this->char_cnt >= $this->hourly_limit) ? true: false;
  }

  function Exceeded() : bool
  {
     return ($this->char_cnt >= $this->hourly_limit) ? true: false;
  }

  function __invoke(string $input)
  {
      $this->char_cnt += \strlen($input);

      $this->current_time = \time();  

      if ($this->current_time > self::$hour_seconds) {

          $this->reset();
      }
 
  }
}


