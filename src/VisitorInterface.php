<?php
declare(strict_types=1);
namespace Vocab;

interface VisitorInterface {

    function accept(InserterInterface $arg) : int;
}