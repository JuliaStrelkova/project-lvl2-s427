<?php
declare(strict_types=1);

namespace Gendiff;

use Symfony\Component\Yaml\Yaml;


function parseYaml(string $pathToFile): array
{
    $data = file_get_contents($pathToFile);

    return Yaml::parse($data);
}

