<?php
declare(strict_types=1);
namespace Vocab;

class MessageLog { 
 
   private \SplFileObject $file;

   private Queue $queue;

   function __construct(\SplFileObject $file)
   {
      $this->file = $file;
      $this->queue = new Queue();  
   }  

   function log(string $msg)
   {
      $this->queue->enqueue($msg);
     
      $this->file->fwrite($msg . "\n");
   }

   function __toString() : string
   {
      return (string) $this->queue;
   }

   // Reports messages
   function report() : void
   {
      $str = (string) $this->queue;
      echo $str;
   }

   function reset()
   {
     $this->queue->empty(); 
   }
}
