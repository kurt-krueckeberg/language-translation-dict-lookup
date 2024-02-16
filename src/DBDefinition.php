<?php
declare(strict_types=1);
namespace Vocab;

readonly class DBDefinition implements DefinitionInterface {
       
    function __construct(public string $definition, public array $expressions=null)
    {
    }
}
