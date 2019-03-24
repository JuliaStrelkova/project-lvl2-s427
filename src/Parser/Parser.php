<?php


namespace Gendiff\Parser;


use RuntimeException;
use Symfony\Component\Yaml\Yaml;

class Parser
{
    public function parse(string $pathToFile): array
    {
        $data = file_get_contents($pathToFile);
        $extension = pathinfo($pathToFile, PATHINFO_EXTENSION);

        switch ($extension) {
            case 'json':
                return json_decode($data, true);

            case 'yaml':
                return Yaml::parse($data);
        }

        throw new RuntimeException('Unexpected data format');
    }
}
