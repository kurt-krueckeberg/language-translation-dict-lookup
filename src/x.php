<?php
declare(strict_types=1);
namespace Vocab;

class NewWordResult implements WordInterface {
       
      private \PDOStatment $results;

      public __construct(\PDOStatemte $stmt)
      {
         $this->results = $stmt;  
      } 

      definitions 
        $this->results->fetch(\PDO::FETCH_ASSOC);
              
              $conj = $result['conjuation'];
       
