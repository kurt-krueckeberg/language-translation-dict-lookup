<?php
declare(strict_types=1);
namespace Vocab;

class Queue {

    private array $queue;

    public function __construct() 
    {
       $this->queue = [];
    }

    public function enqueue(string $item) : void
    {
        array_push($this->queue, $item);
    }

    public function dequeue() : string
    {
        if (!$this->isEmpty()) {

            return array_shift($this->queue);
        }

        return ''; 
    }

    public function peek() : string
    {
        if (!$this->isEmpty()) {

            return $this->queue[0];
        }
        return "";
    }

    function __toString() : string
    {
       return array_reduce($this->queue, function(string|null $result, string $item) : string {
           
                $result .= ($item . "\n");
           
                return $result;
              });
    }
    
    public function isEmpty() : bool
    {
        return empty($this->queue);
    }
    
    public function empty() : void
    {
        unset($this->queue);
    }
}
