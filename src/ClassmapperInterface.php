<?php
declare(strict_types=1);
namespace TranslationTools;

interface ClassmapperInterface {

    public function class_name() : string;
    public function get_provider() : string;
}
