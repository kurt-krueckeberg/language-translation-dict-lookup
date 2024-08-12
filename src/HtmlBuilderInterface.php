<?php
declare(strict_types=1);
namespace Vocab;

interface HtmlBuilderInterface {
   
   public function build_definitions_section(WordInterface $wrface) : string;
   
   public function add_samples_section(string $word, \Traversable $iter) : string;
}
