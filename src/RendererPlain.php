<?php
declare(strict_types=1);

namespace Gendiff;

use RuntimeException;

function renderPlain(?array $data): string
{
    return array_reduce(
        $data,
        function (string $acc, array $item) {
            if ($item['type'] === STATE_UNCHANGED && !isset($item['oldValue'])) {
                $beginString = "Property '{$item['key']}.";

                return $acc . array_reduce(
                    $item['children'],
                    function ($acc, $nestedItem) use ($beginString) {
                        if ($nestedItem['type'] === STATE_CHANGED) {
                            return "$acc$beginString{$nestedItem['key']}' was changed. " .
                                "From '{$nestedItem['oldValue']}' to '{$nestedItem['newValue']}'" . PHP_EOL;
                        }
                        if ($nestedItem['type'] === STATE_DELETED) {
                            return "$acc$beginString{$nestedItem['key']}' was removed" . PHP_EOL;
                        }
                        if ($nestedItem['type'] === STATE_ADDED && !isset($nestedItem['newValue'])) {
                            return "$acc$beginString{$nestedItem['key']}' was added with value: 'complex value'"
                                . PHP_EOL;
                        }
                        if ($nestedItem['type'] === STATE_ADDED) {
                            return "$acc$beginString{$nestedItem['key']}' " .
                                "was added with value: '{$nestedItem['newValue']}'"
                                . PHP_EOL;
                        }
                        if ($nestedItem['type'] === STATE_UNCHANGED) {
                            return (string) $acc;
                        }

                            throw new RuntimeException('Unexpected state value');
                    },
                    ''
                );
            }

            if ($item['type'] === STATE_CHANGED) {
                return $acc .
                    "Property '{$item['key']}' was changed. From '{$item['oldValue']}' to '{$item['newValue']}'"
                    . PHP_EOL;
            }
            if ($item['type'] === STATE_DELETED) {
                return $acc . "Property '{$item['key']}' was removed" . PHP_EOL;
            }
            if ($item['type'] === STATE_ADDED && !isset($item['newValue'])) {
                return $acc . "Property '{$item['key']}' was added with value: 'complex value'" . PHP_EOL;
            }
            if ($item['type'] === STATE_ADDED) {
                return $acc . "Property '{$item['key']}'"
                    . "was added with value: '{$item['newValue']}'" . PHP_EOL;
            }
            if ($item['type'] === STATE_UNCHANGED) {
                return '';
            }
            throw new RuntimeException('Unexpected state value');
        },
        ''
    );
}
