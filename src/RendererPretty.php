<?php
declare(strict_types=1);

namespace Gendiff;

use RuntimeException;

function renderPretty(array $data, int $level = 1): string
{
    $result = array_reduce(
        $data,
        function ($acc, $item) use ($level) {
            switch ($item['type']) {
                case (STATE_UNCHANGED):
                    if (!isset($item['oldValue'])) {
                        $acc = [
                            $acc,
                            renderIndentation($level),
                            $item['key'],
                            ': ',
                            renderPretty($item['children'], $level + 1),
                            PHP_EOL,
                        ];

                        return implode('', $acc);
                    }

                    $acc = [
                        $acc,
                        renderIndentation($level),
                        $item['key'],
                        ': ',
                        renderScalarValue($item['oldValue']),
                        PHP_EOL,
                    ];

                    return implode('', $acc);
                case (STATE_CHANGED):
                    if (isset($item['oldValue'])) {
                        $acc = [
                            $acc,
                            renderIndentation($level, '  + '),
                            $item['key'],
                            ': ',
                            renderScalarValue($item['newValue']),
                            PHP_EOL,
                            renderIndentation($level, '  - '),
                            $item['key'],
                            ': ',
                            renderScalarValue($item['oldValue']),
                            PHP_EOL,
                        ];

                        return implode('', $acc);
                    }

                    $acc = [
                        $acc,
                        renderIndentation($level),
                        $item['key'],
                        ': ',
                        renderPretty($item['children'], $level + 1),
                    ];

                    return implode('', $acc);
                case (STATE_DELETED):
                    if (!isset($item['oldValue'])) {
                        $acc = [
                            $acc,
                            renderIndentation($level, '  - '),
                            $item['key'],
                            ': ',
                            renderArray($item['children'], $level + 1),
                            PHP_EOL,
                        ];

                        return implode('', $acc);
                    }

                    $acc = [
                        $acc,
                        renderIndentation($level, '  - '),
                        $item['key'],
                        ': ',
                        renderScalarValue($item['oldValue']),
                        PHP_EOL,
                    ];

                    return implode('', $acc);
                case (STATE_ADDED):
                    if (!isset($item['newValue'])) {
                        $acc = [
                            $acc,
                            renderIndentation($level, '  + '),
                            $item['key'],
                            ': ',
                            renderArray($item['children'], $level + 1),
                            PHP_EOL,
                        ];

                        return implode('', $acc);
                    }

                    $acc = [
                        $acc,
                        renderIndentation($level, '  + '),
                        $item['key'],
                        ': ',
                        renderScalarValue($item['newValue']),
                        PHP_EOL,
                    ];

                    return implode('', $acc);
            }
            throw new RuntimeException('Unexpected state value');
        },
        ''
    );

    return implode(
        '',
        [
            '{',
            PHP_EOL,
            $result,
            renderIndentation($level, ''),
            '}',
        ]
    );
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
            $acc = [
                $acc,
                '{',
                PHP_EOL,
                renderIndentation($level),
                $key,
                ': ',
                $data[$key],
                PHP_EOL,
                renderIndentation($level - 1),
                '}',
            ];

            return implode('', $acc);
        },
        ''
    );
}
