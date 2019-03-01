<?php
declare(strict_types=1);

namespace Gendiff;

use function Funct\Collection\union;

const STATE_DELETED = 'deleted';
const STATE_ADDED = 'added';
const STATE_CHANGED = 'changed';
const STATE_UNCHANGED = 'unchanged';

function buildAst(array $firstDataSet, array $secondDataSet)
{
    $keysOfFirstDataSet = array_keys($firstDataSet);
    $keysOfSecondDataSet = array_keys($secondDataSet);

    $keysFirstAndSecondDataSet = union($keysOfFirstDataSet, $keysOfSecondDataSet);

    return array_map(
        function ($key) use ($firstDataSet, $secondDataSet) {

            if (array_key_exists($key, $firstDataSet) && array_key_exists($key, $secondDataSet)) {
                if (is_array($firstDataSet[$key]) && ($firstDataSet[$key] === $secondDataSet[$key])) {
                    return [
                        'type' => STATE_UNCHANGED,
                        'key' => $key,
                        'children' => $firstDataSet[$key]
                    ];
                }
                if ($firstDataSet[$key] === $secondDataSet[$key]) {
                    return [
                        'type' => STATE_UNCHANGED,
                        'key' => $key,
                        'oldValue' => $firstDataSet[$key]
                    ];
                }

                if (!is_array($firstDataSet[$key]) && !is_array($secondDataSet[$key])) {
                    return [
                        'type' => STATE_CHANGED,
                        'key' => $key,
                        'oldValue' => $firstDataSet[$key],
                        'newValue' => $secondDataSet[$key],
                    ];
                }

                return [
                    'type' => STATE_UNCHANGED,
                    'key' => $key,
                    'children' => buildAst($firstDataSet[$key], $secondDataSet[$key]),
                ];
            }

            if (array_key_exists($key, $firstDataSet)) {
                if (is_array($firstDataSet[$key])) {
                    return [
                        'type' => STATE_DELETED,
                        'key' => $key,
                        'children' => $firstDataSet[$key]
                    ];
                }
                return [
                    'type' => STATE_DELETED,
                    'key' => $key,
                    'oldValue' => $firstDataSet[$key],
                ];
            }
            if (is_array($secondDataSet[$key])) {
                return [
                    'type' => STATE_ADDED,
                    'key' => $key,
                    'children' => $secondDataSet[$key]
                ];
            }
            return [
                'type' => STATE_ADDED,
                'key' => $key,
                'newValue' => $secondDataSet[$key],
            ];
        },
        $keysFirstAndSecondDataSet
    );
}
