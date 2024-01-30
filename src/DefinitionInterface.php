<?php
declare(strict_types=1);
namespace Vocab;

interface DefinitionInterface {
  function definition() : string;
  function expressions() : array; 
}
