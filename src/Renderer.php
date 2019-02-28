<?php
declare(strict_types=1);

namespace Gendiff;


function render(?array $data): string
{
    $result = '{' . PHP_EOL;

    $result = array_reduce(
        $data,
        function ($acc, $item) {
            if ($item[KEY_STATE] === STATE_UNCHANGED) {
                if (!is_array($item[KEY_OLD_VALUE])) {
                    return $acc . '    ' . $item[KEY] . ': ' . renderScalarValue($item[KEY_OLD_VALUE]) . PHP_EOL;
                }

                return $acc . '    ' . $item[KEY] . ': ' . render($item[KEY_OLD_VALUE]) . PHP_EOL;
            }
            if ($item[KEY_STATE] === STATE_CHANGED) {
                if (isset($item[KEY_OLD_VALUE])) {
                    return $acc
                        . '  + ' . $item[KEY] . ': ' . renderScalarValue($item[KEY_NEW_VALUE]) . PHP_EOL
                        . '  - ' . $item[KEY] . ': ' . renderScalarValue($item[KEY_OLD_VALUE]) . PHP_EOL;
                }

                return $acc . '    ' . $item[KEY] . ': ' . render($item[KEY_NEW_VALUE]);
            }
            if ($item[KEY_STATE] === STATE_DELETE) {
                if (!is_array($item[KEY_OLD_VALUE])) {
                    return $acc . '  - ' . $item[KEY] . ': ' . renderScalarValue($item[KEY_OLD_VALUE]) . PHP_EOL;
                }

                return $acc . '  - ' . $item[KEY] . ': ' . render($item[KEY_OLD_VALUE]) . PHP_EOL;
            }
            if ($item[KEY_STATE] === STATE_ADDED) {
                if (!is_array($item[KEY_NEW_VALUE])) {
                    return $acc . '  + ' . $item[KEY] . ': ' . renderScalarValue($item[KEY_NEW_VALUE]) . PHP_EOL;
                }
                $acc = $acc . '  + ' . $item[KEY];
                array_reduce(
                    array_keys($item[KEY_NEW_VALUE]),
                    function ($acc, $keyInterior) use ($item) {
                        return $acc . render(
                            [
                                    KEY_STATE => STATE_UNCHANGED,
                                    KEY => $keyInterior,
                                    KEY_OLD_VALUE => $item[KEY_NEW_VALUE][$keyInterior],
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
