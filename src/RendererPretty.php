<?php
declare(strict_types=1);

namespace Gendiff;

use RuntimeException;

function renderPretty(?array $data, int $level = 1): string
{
    $result = '{' . PHP_EOL;

    $result = array_reduce(
        $data,
        function ($acc, $item) use ($level) {
            if ($item['type'] === STATE_UNCHANGED) {
                if (!isset($item['oldValue'])) {
                    return $acc . renderIndentation($level) . $item['key']
                        . ': ' . renderPretty($item['children'], $level + 1) . PHP_EOL;
                }

                return $acc . renderIndentation($level) . $item['key']
                    . ': ' . renderScalarValue($item['oldValue']) . PHP_EOL;
            }

            if ($item['type'] === STATE_CHANGED) {
                if (isset($item['oldValue'])) {
                    return $acc
                        . renderIndentation($level, '  + ') . $item['key']
                        . ': ' . renderScalarValue($item['newValue']) . PHP_EOL
                        . renderIndentation($level, '  - ') . $item['key']
                        . ': ' . renderScalarValue($item['oldValue']) . PHP_EOL;
                }

                return $acc . renderIndentation($level) . $item['key']
                    . ': ' . renderPretty($item['children'], $level + 1);
            }

            if ($item['type'] === STATE_DELETED) {
                if (!isset($item['oldValue'])) {
                    return $acc . renderIndentation($level, '  - ') . $item['key']
                        . ': ' . renderArray($item['children'], $level + 1) . PHP_EOL;
                }

                return $acc . renderIndentation($level, '  - ') . $item['key']
                    . ': ' . renderScalarValue($item['oldValue']) . PHP_EOL;
            }

            if ($item['type'] === STATE_ADDED) {
                if (!isset($item['newValue'])) {
                    return $acc . renderIndentation($level, '  + ') . $item['key']
                        . ': ' . renderArray($item['children'], $level + 1) . PHP_EOL;
                }

                return $acc . renderIndentation($level, '  + ') . $item['key']
                    . ': ' . renderScalarValue($item['newValue']) . PHP_EOL;
            }

            throw new RuntimeException('Unexpected state value');
        },
        $result
    );

    return $result . renderIndentation($level, '') . '}';
}

function renderIndentation(int $level, string $suffix = '    '): string
{
    return str_repeat('    ', $level - 1) . $suffix;
}

function renderScalarValue($value): string
{
    if (is_bool($value)) {
        return ($value === true) ? 'true' : 'false';
    }

    return (string) $value;
}

function renderArray(array $data, int $level): string
{
    $keys = array_keys($data);

    return array_reduce(
        $keys,
        function ($acc, $key) use ($data, $level) {
            return $acc . '{' . PHP_EOL . renderIndentation($level) . $key . ': '
                . $data[$key] . PHP_EOL . renderIndentation($level - 1) . '}';
        },
        ''
    );
}
