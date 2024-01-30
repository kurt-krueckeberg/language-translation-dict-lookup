<?php
declare(strict_types=1);
namespace Vocab;
use Vocab\AbstractDefinitionsIterator;

readonly class SystranWordResult implements WordResultInterface, VisitorInterface {

   public array $match;

   function __construct(array $match)
   { 
      $this->match = $match;
   }

   static public function create($match) : SystranWordResult
   {
      return match($match['source']['pos']) {
         'noun' => new SystranNounResult($match),
         'verb' => new SystranVerbResult($match, function() use($match) : string { 
                return $match['source']['inflection'];}
           ),
         default => new SystranWordResult($match)
      };
   }

   static public function create_verbFamily(array $matches, int $main_verb_index) : SystranWordResult
   {
      return new SystranVerbFamilyResult($matches, $main_verb_index);
   }
  
   function get_pos() : Pos
   {
       return Pos::fromString($this->match['source']['pos']);
   } 

   function word_defined() : string
   {
     $tmp = $this->match['source']['lemma'];

     // strip out any tilde or anything that is not a-zaööü. Keep ' ' for  reflexive verbs like
     // 'sich vorstellen'
     return preg_replace('/[^A-ZÖÄÜa-zaöü ]/', '', $tmp); 
   }

   function definitions() : AbstractDefinitionsIterator
   {
      return new SystranDefinitionsIterator($this->match['targets']); 
   }

   function accept(InserterInterface $inserter) : int
   {
       return $inserter->insert_word($this);
   }
}
