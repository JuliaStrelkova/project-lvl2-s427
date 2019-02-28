<?php
declare(strict_types=1);

namespace Gendiff;

use RuntimeException;
use Symfony\Component\Yaml\Yaml;


function genDiff(string $pathToFile1, string $pathToFile2): string
{
    $firstDataSet = getDataSet($pathToFile1);
    $secondDataSet = getDataSet($pathToFile2);

    return render(buildAst($firstDataSet, $secondDataSet));
}

function getDataSet(string $pathToFile)
{
    $extension = pathinfo($pathToFile, PATHINFO_EXTENSION);
    $data = file_get_contents($pathToFile);

    return parser($data, $extension);
}

function parser(string $data, string $format): array
{
    if ($format === 'json') {
        return json_decode($data, true);
    }

    if ($format === 'yaml') {
        return Yaml::parse($data);
    }

    throw new RuntimeException('Unexpected data format');
}
