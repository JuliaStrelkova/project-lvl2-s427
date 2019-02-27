<?php

declare(strict_types=1);

namespace Gendiff;


function render(?array $data): string
{
    $result = '{' . PHP_EOL;

    $result = array_reduce(
        $data,
        function ($acc, $item) {
            if ($item['state'] === 'notChanged') {
                if (!is_array($item['oldValue'])) {
                    return $acc . '    ' . $item['key'] . ': ' . renderScalarValue($item['oldValue']) . PHP_EOL;
                }

                return $acc . '    ' . $item['key'] . ': ' . render($item['oldValue']) . PHP_EOL;
            }
            if ($item['state'] === 'changed') {
                if (isset($item['oldValue'])) {
                    return $acc
                        . '  + ' . $item['key'] . ': ' . renderScalarValue($item['newValue']) . PHP_EOL
                        . '  - ' . $item['key'] . ': ' . renderScalarValue($item['oldValue']) . PHP_EOL;
                }

                return $acc . '    ' . $item['key'] . ': ' . render($item['newValue']);
            }
            if ($item['state'] === 'deleted') {
                if (!is_array($item['oldValue'])) {
                    return $acc . '  - ' . $item['key'] . ': ' . renderScalarValue($item['oldValue']) . PHP_EOL;
                }

                return $acc . '  - ' . $item['key'] . ': ' . render($item['oldValue']) . PHP_EOL;
            }
            if ($item['state'] === 'added') {
                if (!is_array($item['newValue'])) {
                    return $acc . '  + ' . $item['key'] . ': ' . renderScalarValue($item['newValue']) . PHP_EOL;
                }
                $acc = $acc . '  + ' . $item['key'];
                array_reduce(
                    array_keys($item['newValue']),
                    function ($acc, $keyInterior) use ($item) {
                        return $acc . render(
                            [
                                'state' => 'notChanged',
                                'key' => $keyInterior,
                                'oldValue' => $item['newValue'][$keyInterior],
                            ]
                        ) . PHP_EOL;
                    },
                    $acc
                );
            }

            return [];
        },
        $result
    );

    return $result . '}';
}

function renderScalarValue($value): string
{
    if (is_bool($value)) {
        return ($value === true) ? 'true' : 'false';
    }

    return (string)$value;
}
