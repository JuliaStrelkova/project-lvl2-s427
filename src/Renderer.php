<?php
declare(strict_types=1);

namespace Gendiff;


use RuntimeException;

function render(?array $data, int $level = 1): string
{
    $result = '{' . PHP_EOL;

    $result = array_reduce(
        $data,
        function ($acc, $item) use ($level) {
            if ($item['state'] === STATE_UNCHANGED) {
                if (is_array($item['oldValue'])) {
                    return $acc . renderIndentation($level) . $item['key'] . ': ' . render($item['oldValue'], $level + 1) . PHP_EOL;
                }

                return $acc . renderIndentation($level) . $item['key'] . ': ' . renderScalarValue($item['oldValue']) . PHP_EOL;
            }

            if ($item['state'] === STATE_CHANGED) {
                if (isset($item['oldValue'])) {
                    return $acc
                        . renderIndentation($level, '  + ') . $item['key'] . ': ' . renderScalarValue($item['newValue']) . PHP_EOL
                        . renderIndentation($level, '  - ') . $item['key'] . ': ' . renderScalarValue($item['oldValue']) . PHP_EOL;
                }

                return $acc . renderIndentation($level) . $item['key'] . ': ' . render($item['newValue'], $level + 1);
            }

            if ($item['state'] === STATE_DELETED) {
                if (is_array($item['oldValue'])) {
                    return $acc . renderIndentation($level, '  - ') . $item['key'] . ': ' . render($item['oldValue'], $level + 1) . PHP_EOL;
                }

                return $acc . renderIndentation($level, '  - ') . $item['key'] . ': ' . renderScalarValue($item['oldValue']) . PHP_EOL;
            }

            if ($item['state'] === STATE_ADDED) {
                if (is_array($item['newValue'])) {
                    return $acc . renderIndentation($level, '  + ') . $item['key'] . ': ' . render($item['newValue'], $level + 1) . PHP_EOL;
                }

                return $acc . renderIndentation($level, '  + ') . $item['key'] . ': ' . renderScalarValue($item['newValue']) . PHP_EOL;
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

    return (string)$value;
}
