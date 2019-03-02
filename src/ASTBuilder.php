<?php
declare(strict_types=1);

namespace Gendiff;

const STATE_DELETED = 'deleted';
const STATE_ADDED = 'added';
const STATE_CHANGED = 'changed';
const STATE_UNCHANGED = 'unchanged';

function buildAst(array $firstDataSet, array $secondDataSet): array
{
    $keysFirstAndSecondDataSet = getUniqueKeys($firstDataSet, $secondDataSet);

    return array_map(
        function (string $key) use ($firstDataSet, $secondDataSet) {

            if (array_key_exists($key, $firstDataSet) && array_key_exists($key, $secondDataSet)) {
                if (is_array($firstDataSet[$key]) && ($firstDataSet[$key] === $secondDataSet[$key])) {
                    return buildNode(STATE_UNCHANGED, $key, null, null, $firstDataSet[$key]);
                }

                if ($firstDataSet[$key] === $secondDataSet[$key]) {
                    return buildNode(STATE_UNCHANGED, $key, $firstDataSet[$key]);
                }

                if (!is_array($firstDataSet[$key]) && !is_array($secondDataSet[$key])) {
                    return buildNode(STATE_CHANGED, $key, $firstDataSet[$key], $secondDataSet[$key]);
                }

                return buildNode(
                    STATE_UNCHANGED,
                    $key,
                    null,
                    null,
                    buildAst($firstDataSet[$key], $secondDataSet[$key])
                );
            }

            if (array_key_exists($key, $firstDataSet)) {
                if (is_array($firstDataSet[$key])) {
                    return buildNode(STATE_DELETED, $key, null, null, $firstDataSet[$key]);
                }

                return buildNode(STATE_DELETED, $key, $firstDataSet[$key]);
            }
            if (is_array($secondDataSet[$key])) {
                return buildNode(STATE_ADDED, $key, null, null, $secondDataSet[$key]);
            }

            return buildNode(STATE_ADDED, $key, null, $secondDataSet[$key]);
        },
        $keysFirstAndSecondDataSet
    );
}

function buildNode(string $type, string $key, $oldValue = null, $newValue = null, $children = null): array
{
    return [
        'type' => $type,
        'key' => $key,
        'newValue' => $newValue,
        'oldValue' => $oldValue,
        'children' => $children,
    ];
}

function getUniqueKeys(array $firstDataSet, array $secondDataSet): array
{
    return array_values(
        array_unique(
            array_merge(array_keys($firstDataSet), array_keys($secondDataSet))
        )
    );
}
