<?php
declare(strict_types=1);

namespace Gendiff;

use RuntimeException;

function renderPlain(array $data, array $keyChain = []): string
{
    $rows = array_reduce(
        $data,
        function (array $acc, array $item) use ($keyChain) {
            $keyChain[] = $item['key'];

            switch ($item['type']) {
                case NESTED:
                    $acc[] = renderPlain($item['children'], $keyChain);

                    break;
                case UNCHANGED:
                    break;
                case CHANGED:
                    $keys = implode('.', $keyChain);
                    $acc[] = "Property '$keys' was changed. From '{$item['oldValue']}' to '{$item['newValue']}'"
                        . PHP_EOL;
                    break;
                case DELETED:
                    $keys = implode('.', $keyChain);
                    $acc[] = "Property '$keys' was removed" . PHP_EOL;

                    break;
                case ADDED:
                    $value = is_array($item['newValue']) ? 'complex value' : $item['newValue'];
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
