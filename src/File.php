<?php
declare(strict_types=1);
namespace Vocab;

class File extends \SplFileObject  {

   public function __construct(string $fname, string $mode)
   {
       parent::__construct($fname, $mode);
       
       $this->setFlags(\SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY | \SplFileObject::DROP_NEW_LINE);
   }
}
