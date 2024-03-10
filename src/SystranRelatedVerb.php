<?php
declare(strict_types=1);
namespace Vocab;

readonly class SystranRelatedVerb extends SystranWord implements RelatedVerbInterface  {

   private string $main_verb;

   private static $select_main_verb_id = "select w.id as id from words as w where w.words=:word";

   private static $word = '';

   private $word_id;

   use get_stmt_trait;

   function __construct(array $match)
   { 
      parent::__construct($match);

      $this->main_verb = $match['source']['term'];

      $stmt = $this->get_stmt('select_main_verb_id');
 
      self::$word = $this->main_verb;

      $result = $stmt->execute();
      
      $array = $result->fetch(\PDO::FETCH_ASSOC);

      $this->word_id = $array['id'];
   }

   protected function bind(\PDOStatement $stmt) : void
   {  
      $stmt->bindParam(':word', self::$word, \PDO::PARAM_STR);           
   }
 
   function main_verb() : string
   {
      return $this->main_verb; 
   }

   function main_verb_id() : int
   {
      return $this->word_id; 
   }

   function accept(InserterInterface $inserter) : int
   {
       return $inserter->insert_related_verb($this);
   }
}
