<?php
declare(strict_types=1);
namespace Vocab;

class Derived extends AbstractSample { 
    
    public function __construct(array $matches)
    {
       parent::__construct($matches);
    } 
       
   protected function get_current(mixed $current) : string | false
   {
       return "Prefix-" . $current;
   }
}
