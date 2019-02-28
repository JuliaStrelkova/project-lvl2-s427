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
                        'state' => STATE_UNCHANGED,
                        'key' => $key,
                        'oldValue' => buildNestedAst($firstDataSet[$key])
                    ];
                }
                if ($firstDataSet[$key] === $secondDataSet[$key]) {
                    return [
                        'state' => STATE_UNCHANGED,
                        'key' => $key,
                        'oldValue' => $firstDataSet[$key]
                    ];
                }

                if (!is_array($firstDataSet[$key]) && !is_array($secondDataSet[$key])) {
                    return [
                        'state' => STATE_CHANGED,
                        'key' => $key,
                        'oldValue' => $firstDataSet[$key],
                        'newValue' => $secondDataSet[$key],
                    ];
                }

                return [
                    'state' => STATE_UNCHANGED,
                    'key' => $key,
                    'oldValue' => buildAst($firstDataSet[$key], $secondDataSet[$key]),
                ];
            }

            if (array_key_exists($key, $firstDataSet)) {
                if (is_array($firstDataSet[$key])) {
                    return [
                        'state' => STATE_DELETED,
                        'key' => $key,
                        'oldValue' => buildNestedAst($firstDataSet[$key])
                    ];
                }
                return [
                    'state' => STATE_DELETED,
                    'key' => $key,
                    'oldValue' => $firstDataSet[$key],
                ];
            }
            if (is_array($secondDataSet[$key])) {
                return [
                    'state' => STATE_ADDED,
                    'key' => $key,
                    'newValue' => buildNestedAst($secondDataSet[$key])
                ];
            }
            return [
                'state' => STATE_ADDED,
                'key' => $key,
                'newValue' => $secondDataSet[$key],
            ];
        },
        $keysFirstAndSecondDataSet
    );
}

function buildNestedAst(array $data): array
{
    $keys = array_keys($data);
    return array_map(function ($key) use ($data) {
        if (is_array($data[$key])) {
            return buildNestedAst($data[$key]);
        }
        return [
           'state' => STATE_UNCHANGED,
           'key' => $key,
           'oldValue' => $data[$key]
        ];
    }, $keys);
}
