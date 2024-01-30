<?php
declare(strict_types=1);
namespace Vocab;

readonly class SystranDefinition implements DefinitionInterface {

  public array $target;

  function __construct(array $target) 
  {
    $this->target = $target;
  }
  function definition() : string
  {
    return $this->target['lemma'];
  }

  function expressions() : array
  {
    return $this->target['expressions'];
  }
}
