<?php
declare(strict_types=1);

namespace Gendiff;

use RuntimeException;

function renderPretty(array $data, int $level = 0): string
{
    $indentation = renderIndentation($level);

    $result = array_reduce(
        $data,
        function (string $acc, array $item) use ($level, $indentation) {
            $old = renderValue($item['oldValue'], $level);
            $new = renderValue($item['newValue'], $level);

            switch ($item['type']) {
                case NESTED:
                    $renderedChildren = renderPretty($item['children'], $level + 1);

                    return implode('', [$acc, $indentation, '    ', $item['key'], ': ', $renderedChildren, PHP_EOL]);

                case UNCHANGED:
                    return implode('', [$acc, $indentation, '    ', $item['key'], ': ', $old, PHP_EOL]);

                case CHANGED:
                    $rowNew = implode('', [$indentation, '  + ', $item['key'], ': ', $new, PHP_EOL]);
                    $rowOld = implode('', [$indentation, '  - ', $item['key'], ': ', $old, PHP_EOL]);

                    return implode('', [$acc, $rowNew, $rowOld]);

                case $item['type'] === DELETED:
                    return implode('', [$acc, $indentation, '  - ', $item['key'], ': ', $old, PHP_EOL]);

                case $item['type'] === ADDED:
                    return implode('', [$acc, $indentation, '  + ', $item['key'], ': ', $new, PHP_EOL]);
            }
            throw new RuntimeException('Unexpected state value');
        },
        ''
    );

    return implode('', ['{', PHP_EOL, $result, $indentation, '}',]);
}

function renderIndentation(int $level): string
{
    return str_repeat('    ', $level);
}

function renderValue($value, int $level): string
{
    return is_array($value) ? renderArray($value, $level) : renderScalarValue($value);
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
            $acc = [
                $acc,
                '{',
                PHP_EOL,
                renderIndentation($level + 2),
                $key,
                ': ',
                $data[$key],
                PHP_EOL,
                renderIndentation($level + 1),
                '}',
            ];

            return implode('', $acc);
        },
        ''
    );
}
