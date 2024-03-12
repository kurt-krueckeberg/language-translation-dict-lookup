<?php
declare(strict_types=1);
namespace Vocab;

enum PartOfSpeech : string  { 
 
   case noun = 'noun';
   case verb = 'verb';
   case adj = 'adj';
   case adv = 'adv';
   case conj = 'conj';
   case other = 'other';
}
