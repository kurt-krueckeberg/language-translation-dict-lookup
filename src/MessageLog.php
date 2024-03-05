<?php
declare(strict_types=1);
namespace Vocab;

use \SplFileObject as File;

class MessageLog { 
 
   private File $file;

   private Queue $queue;

   function __construct()
   {
      $this->file = new File("log.txt", "w");
      $this->queue = new Queue();  
   }  

   function add(string $msg)
   {
      $this->queue->enqueue($msg);
   }

   function __toString() : string
   {
      return (string) $this->queue;
   }

   function report() : string
   {
      $str = (string) $this->queue;
      return $str;
   }

   function reset()
   {
     $this->queue->empty(); 
   }
}
