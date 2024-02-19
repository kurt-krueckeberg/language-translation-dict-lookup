<?php
declare(strict_types=1);
namespace Vocab;

class FetchSamples {
   
   private \PDOStatement $select_samples; 
   
   private static $sql_samples = "select samples.sample as sample from 
       words
         inner join
      samples
         on words.id=samples.word_id
         where words.id=:word_id";
                          
   private \PDO $pdo;
   
   private static int $word_id = -1 ;
   
   private array $rows;
   
   public static function SamplesGenerator(array $samples) : \Traversable
   {
       foreach($samples as $index => $sample) {
           
           yield $sample;
       }
   }

   public function __construct(\PDO $pdo)
   {
      $this->pdo = $pdo;

      $this->select_samples = $pdo->prepare(self::$sql_samples);
      
      $this->select_samples->bindParam(':word_id', self::$word_id, \PDO::PARAM_STR);     
   }

   function __invoke(int $word_id) : \Traversable //array | false
   {
      self::$word_id = $word_id;
      
      $rc = $this->select_samples->execute();
      
      if ($rc == false) {
          
          return array(false, false);
      }
      
      $samples = $this->select_samples->fetchAll(\PDO::FETCH_ASSOC);
      
      return FetchSamples::SamplesGenerator(array_column($samples, 'sample'));
   }
}
