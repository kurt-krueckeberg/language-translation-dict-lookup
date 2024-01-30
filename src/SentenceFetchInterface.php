<?php
declare(strict_types=1);
namespace Vocab;

interface SentenceFetchInterface  { 
   public function fetch_samples(string $word, int $count=3) : \Iterator; //ResultsIterator;
}
