<?php
declare(strict_types=1);

namespace Gendiff;

use function Funct\Collection\union;


function buildAst($firstDataSet, $secondDataSet)
{
    $keysOfFirstDataSet = array_keys($firstDataSet);
    $keysOfSecondDataSet = array_keys($secondDataSet);

    $keysFirstAndSecondDataSet = union($keysOfFirstDataSet, $keysOfSecondDataSet);

    $result = array_map(
        function ($key) use ($firstDataSet, $secondDataSet) {
            if (array_key_exists($key, $firstDataSet) && array_key_exists($key, $secondDataSet)) {
                if ($firstDataSet[$key] === $secondDataSet[$key]) {
                    return [
                        'state' => 'notChanged',
                        'key' => $key,
                        'oldValue' => $firstDataSet[$key],
                    ];
                }

                if (!is_array($firstDataSet[$key]) && !is_array($secondDataSet[$key])) {
                    return [
                        'state' => 'changed',
                        'key' => $key,
                        'oldValue' => $firstDataSet[$key],
                        'newValue' => $secondDataSet[$key],
                    ];
                }

                return [
                    'state' => 'changed',
                    'key' => $key,
                    'newValue' => buildAst($firstDataSet[$key], $secondDataSet[$key]),
                ];
            }
            if (array_key_exists($key, $firstDataSet)) {
                return [
                    'state' => 'deleted',
                    'key' => $key,
                    'oldValue' => $firstDataSet[$key],
                ];
            }

            return [
                'state' => 'added',
                'key' => $key,
                'newValue' => $secondDataSet[$key],
            ];
        },
        $keysFirstAndSecondDataSet
    );

    return $result;
}
