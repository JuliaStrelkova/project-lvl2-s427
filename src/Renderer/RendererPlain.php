<?php
declare(strict_types=1);

namespace Gendiff\Renderer;

use Gendiff\ASTBuilder;
use RuntimeException;

class RendererPlain implements Renderer
{
    public function render(array $data, array $keyChain = []): string
    {
        $rows = array_reduce(
            $data,
            function (array $acc, array $item) use ($keyChain) {
                $keyChain[] = $item['key'];

                switch ($item['type']) {
                    case ASTBuilder::NESTED:
                        $acc[] = $this->render($item['children'], $keyChain);

                        break;
                    case ASTBuilder::UNCHANGED:
                        break;
                    case ASTBuilder::CHANGED:
                        $keys = implode('.', $keyChain);
                        $acc[] = "Property '$keys' was changed. From '{$item['oldValue']}' to '{$item['newValue']}'"
                            . PHP_EOL;
                        break;
                    case ASTBuilder::DELETED:
                        $keys = implode('.', $keyChain);
                        $acc[] = "Property '$keys' was removed" . PHP_EOL;

                        break;
                    case ASTBuilder::ADDED:
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
}
