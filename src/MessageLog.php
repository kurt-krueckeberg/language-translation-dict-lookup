<?php
declare(strict_types=1);
namespace Vocab;

use \SplFileObject as File;

class MessageLog { 
 
   private File $file;

   private Queue $queue;

   function __construct(File $file)
   {
      $this->file = $file;
      $this->queue = new Queue();  
   }  

   function log(string $msg)
   {
      $this->queue->enqueue($msg);
      
      $this->file->fwrite($msg);
   }

   function __toString() : string
   {
      return (string) $this->queue;
   }

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
