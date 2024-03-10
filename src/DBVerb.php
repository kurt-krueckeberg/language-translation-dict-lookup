<?php
declare(strict_types=1);
namespace Vocab;

class DBVerb extends DBWord implements VerbInterface {
    
   private array $row; 

   function __construct(\PDO $pdo, array $row)
   {
      parent::__construct($pdo, $row);
      $this->row = $row;
   }
   
   public function get_pos() : Pos
   {
      return Pos::fromString($this->row['pos']);
   }

   function conjugation() : string
   {
     return $this->row['conjugation'];
   }

   function word_defined() : string
   {
     return $this->row['word'];
   } 
}