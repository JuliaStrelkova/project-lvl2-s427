<?php
declare(strict_types=1);

namespace Gendiff;

use function Funct\Collection\union;
const KEY_STATE = 'state';
const KEY = 'key';
const KEY_OLD_VALUE = 'oldValue';
const KEY_NEW_VALUE = 'newValue';
const STATE_DELETE = 'deleted';
const STATE_ADDED = 'added';
const STATE_CHANGED = 'changed';
const STATE_UNCHANGED = 'unChanged';

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
                        KEY_STATE => STATE_UNCHANGED,
                        KEY => $key,
                        KEY_OLD_VALUE => $firstDataSet[$key],
                    ];
                }

                if (!is_array($firstDataSet[$key]) && !is_array($secondDataSet[$key])) {
                    return [
                        KEY_STATE => STATE_CHANGED,
                        KEY => $key,
                        KEY_OLD_VALUE => $firstDataSet[$key],
                        KEY_NEW_VALUE => $secondDataSet[$key],
                    ];
                }

                return [
                    KEY_STATE => STATE_CHANGED,
                    KEY => $key,
                    KEY_NEW_VALUE => buildAst($firstDataSet[$key], $secondDataSet[$key]),
                ];
            }
            if (array_key_exists($key, $firstDataSet)) {
                return [
                    KEY_STATE => STATE_DELETE,
                    KEY => $key,
                    KEY_OLD_VALUE => $firstDataSet[$key],
                ];
            }

            return [
                KEY_STATE => STATE_ADDED,
                KEY => $key,
                KEY_NEW_VALUE => $secondDataSet[$key],
            ];
        },
        $keysFirstAndSecondDataSet
    );

    return $result;
}
