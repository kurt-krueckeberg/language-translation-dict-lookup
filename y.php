<?php
declare(strict_types=1);
use \SplFileObject as File;

use Vocab\{PartOfSpeech, Pos};

include 'vendor/autoload.php';
 
function test1(PartOfSpeech $pos) : string
{
  $str = 'empty';

  $str = match ($pos) {
       PartOfSpeech::noun => "is noun",
       PartOfSpeech::verb => "is verb",
       default => "neither noun nor verb"
 };
 
 return $str; 
}

function test2(Pos $pos) : string
{
  $str = 'empty';

  $str = match ($pos) {
       Pos::noun => "is noun",
       Pos::verb => "is verb",
       default => "neither noun nor verb"
 };

 return $str;
}



/*

  $some_enum->name returns its string.
  PartOfSpeech::from('noun') returns the enumeration.
*/

  $pos1 = PartOfSpeech::noun;

  echo "\$pos1 is PartOfSpeech::noun. \$pos1->name = ". $pos1->name . "\n";

  $x = PartOfSpeech::from('noun');

  echo "PartOfSpeech::from('noun') = {$x->name}\n";

  $pos2 = Pos::noun;

  echo "\$pos2 is Pos::noun. \$pos2->name = {$pos2->name}\n";

  echo "Testing match statement for PartOfSpeech.\n";

  echo test1($pos1);

  echo "\nTesting match statement for Pos.\n";

  echo test2($pos2);
