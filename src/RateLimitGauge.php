<?php
declare(strict_types=1);
namespace Vocab;

class RateLimitGauge  {

  private int $char_cnt; // Number of characters translated so far in the current hour.

  private int $hourly_limit;  // Number of characters permitted in a hour?

  private static $hour_seconds = 3600;

  private $time_start;

  function __construct(int $limit)
  {
     $this->char_cnt = 0;

     $this->hourly_limit = $limit;

     $this->time_start = \time();
  }

  function reset() : void
  {
     $this->char_cnt = 0; 
     $this->time_start = \time();
  }

  private function willExceed(int $str_length) : bool
  {
     return ($str_length + $this->char_cnt >= $this->hourly_limit) ? true: false;
  }

  function wait_time() : int
  {
    $cur_time = \time();
    return $cur_time = $this->start_time;
  }

  function wait() : void
  {
    $cur_time = \time();
    $wait = $cur_time = $this->start_time;

    \sleep($wait);

    $this->reset();
  }

  function __invoke(string $input) : bool
  {
      $this->char_cnt += \strlen($input);

      if (\time() - $this->time_start > self::$hour_seconds) {

          $this->reset();
      }

      $str_len = \strlen($input);
 
      if ($this->willExceed($str_len)) {
      
          $rc = false;

      } else {

          $this->char_cnt += $str_len;
          $rc = true;
      }  

      return $rc;
  }
}


