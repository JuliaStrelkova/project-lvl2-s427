<?php
declare(strict_types=1);

namespace Gendiff;

const DELETED = 'deleted';
const ADDED = 'added';
const CHANGED = 'changed';
const UNCHANGED = 'unchanged';

function buildAst(array $firstDataSet, array $secondDataSet): array
{
    $keysFirstAndSecondDataSet = getUniqueKeys($firstDataSet, $secondDataSet);

    return array_map(
        function (string $key) use ($firstDataSet, $secondDataSet) {

            if (array_key_exists($key, $firstDataSet) && array_key_exists($key, $secondDataSet)) {
                if (is_array($firstDataSet[$key]) && ($firstDataSet[$key] === $secondDataSet[$key])) {
                    return buildNode(UNCHANGED, $key, null, null, $firstDataSet[$key]);
                }

                if ($firstDataSet[$key] === $secondDataSet[$key]) {
                    return buildNode(UNCHANGED, $key, $firstDataSet[$key]);
                }

                if (!is_array($firstDataSet[$key]) && !is_array($secondDataSet[$key])) {
                    return buildNode(CHANGED, $key, $firstDataSet[$key], $secondDataSet[$key]);
                }

                return buildNode(
                    UNCHANGED,
                    $key,
                    null,
                    null,
                    buildAst($firstDataSet[$key], $secondDataSet[$key])
                );
            }

            if (array_key_exists($key, $firstDataSet)) {
                if (is_array($firstDataSet[$key])) {
                    return buildNode(DELETED, $key, null, null, $firstDataSet[$key]);
                }

                return buildNode(DELETED, $key, $firstDataSet[$key]);
            }
            if (is_array($secondDataSet[$key])) {
                return buildNode(ADDED, $key, null, null, $secondDataSet[$key]);
            }

            return buildNode(ADDED, $key, null, $secondDataSet[$key]);
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
