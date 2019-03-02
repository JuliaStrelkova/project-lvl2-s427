<?php
declare(strict_types=1);

namespace Gendiff;

use RuntimeException;

function renderPlain(array $data, array $keyChain = []): string
{
    $rows = array_reduce(
        $data,
        function ($acc, $item) use ($keyChain) {
            $keyChain[] = $item['key'];

            switch ($item['type']) {
                case STATE_UNCHANGED:
                    if (isset($item['children'])) {
                        $acc[] = renderPlain($item['children'], $keyChain);
                    }

                    break;
                case STATE_CHANGED:
                    if (!isset($item['children'])) {
                        $keys = implode('.', $keyChain);
                        $acc[] = "Property '$keys' was changed. From '{$item['oldValue']}' to '{$item['newValue']}'"
                            . PHP_EOL;
                    }
                    break;
                case STATE_DELETED:
                    $keys = implode('.', $keyChain);
                    $acc[] = "Property '$keys' was removed" . PHP_EOL;

                    break;
                case STATE_ADDED:
                    $value = isset($item['children']) ? 'complex value' : $item['newValue'];
                    $keys = implode('.', $keyChain);
                    $acc[] = "Property '$keys' was added with value: '$value'" . PHP_EOL;
                    break;
                default:
                    throw new RuntimeException('Unexpected node type');
            }

            return $acc;
        },
        []
    );

    return implode('', $rows);
}
