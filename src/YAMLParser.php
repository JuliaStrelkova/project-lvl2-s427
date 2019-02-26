<?php


namespace Gendiff;

use Symfony\Component\Yaml\Yaml;


class YAMLParser implements Parser
{
    public function parse(string $pathToFile): array
    {
        $data = file_get_contents($pathToFile);
        return Yaml::parse($data);
    }
}
