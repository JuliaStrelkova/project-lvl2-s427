<?php
declare(strict_types=1);

namespace Gendiff;

function genDiff(string $pathToFile1, string $pathToFile2): string
{
    $firstDataSet = getDataSet($pathToFile1);
    $secondDataSet = getDataSet($pathToFile2);

    $keysOfFirstDataSet = array_keys($firstDataSet);
    $keysOfSecondDataSet = array_keys($secondDataSet);

    $result = array_reduce(
        $keysOfFirstDataSet,
        function ($acc, $key) use ($firstDataSet, $keysOfSecondDataSet, $secondDataSet) {
            if (!in_array($key, $keysOfSecondDataSet, true)) {
                $acc['- ' . $key] = $firstDataSet[$key];

                return $acc;
            }

            if ($firstDataSet[$key] !== $secondDataSet[$key]) {
                $acc['+ ' . $key] = $secondDataSet[$key];
                $acc['- ' . $key] = $firstDataSet[$key];

                return $acc;
            }
            $acc['  ' . $key] = $firstDataSet[$key];

            return $acc;
        },
        []
    );

    $result = array_reduce(
        $keysOfSecondDataSet,
        function ($acc, $key) use ($keysOfFirstDataSet, $secondDataSet) {
            if (!in_array($key, $keysOfFirstDataSet, true)) {
                $acc['+ ' . $key] = $secondDataSet[$key];
            }

            return $acc;
        },
        $result
    );

    return render($result);
}

function render(array $data): string
{
    $result = '{' . PHP_EOL;

    $result = array_reduce(
        array_keys($data),
        function ($acc, $key) use ($data) {
            $value = $data[$key];
            if (is_bool($data[$key])) {
                $value = $data[$key] ? 'true' : 'false';
            }
            $acc .= "  {$key}: {$value}" . PHP_EOL;

            return $acc;
        },
        $result
    );

    return $result . '}';
}

function getDataSet(string $path): array
{
    $extension = pathinfo($path, PATHINFO_EXTENSION);

    if ($extension === 'json') {
        return parseJson($path);
    }

    if ($extension === 'yaml') {
        return parseYaml($path);
    }

    return [];
}

