<?php
declare(strict_types=1);

namespace Gendiff;

use RuntimeException;
use Symfony\Component\Yaml\Yaml;

function genDiff(string $pathToFile1, string $pathToFile2, string $format): string
{
    $firstDataSet = getDataSet($pathToFile1);
    $secondDataSet = getDataSet($pathToFile2);

    $ast = buildAst($firstDataSet, $secondDataSet);

    return render($ast, $format);
}

function getDataSet(string $pathToFile)
{
    $extension = pathinfo($pathToFile, PATHINFO_EXTENSION);
    $data = file_get_contents($pathToFile);

    return parse($data, $extension);
}

function render(array $ast, string $format): string
{
    switch ($format) {
        case 'pretty':
            return renderPretty($ast);

        case 'plain':
            return renderPlain($ast);

        case 'json':
            return json_encode($ast);
    }

    throw new RuntimeException('Unexpected data format');
}

function parse(string $data, string $format): array
{
    switch ($format) {
        case 'json':
            return json_decode($data, true);

        case 'yaml':
            return Yaml::parse($data);
    }

    throw new RuntimeException('Unexpected data format');
}
