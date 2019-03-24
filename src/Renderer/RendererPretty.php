<?php
declare(strict_types=1);

namespace Gendiff\Renderer;

use Gendiff\ASTBuilder;
use RuntimeException;

class RendererPretty implements Renderer
{
    public function render(array $data, int $level = 0): string
    {
        $indentation = $this->renderIndentation($level);

        $result = array_reduce(
            $data,
            function (string $acc, array $item) use ($level, $indentation) {
                $oldRenderedValue = $this->renderValue($item['oldValue'], $level);
                $newRenderedValue = $this->renderValue($item['newValue'], $level);

                switch ($item['type']) {
                    case ASTBuilder::NESTED:
                        $renderedChildren = $this->render($item['children'], $level + 1);

                        return implode(
                            '',
                            [$acc, $indentation, '    ', $item['key'], ': ', $renderedChildren, PHP_EOL]
                        );

                    case ASTBuilder::UNCHANGED:
                        return implode(
                            '',
                            [$acc, $indentation, '    ', $item['key'], ': ', $oldRenderedValue, PHP_EOL]
                        );

                    case ASTBuilder::CHANGED:
                        $rowNew = implode('', [$indentation, '  + ', $item['key'], ': ', $newRenderedValue, PHP_EOL]);
                        $rowOld = implode('', [$indentation, '  - ', $item['key'], ': ', $oldRenderedValue, PHP_EOL]);

                        return implode('', [$acc, $rowNew, $rowOld]);

                    case ASTBuilder::DELETED:
                        return implode(
                            '',
                            [$acc, $indentation, '  - ', $item['key'], ': ', $oldRenderedValue, PHP_EOL]
                        );

                    case ASTBuilder::ADDED:
                        return implode(
                            '',
                            [$acc, $indentation, '  + ', $item['key'], ': ', $newRenderedValue, PHP_EOL]
                        );
                }
                throw new RuntimeException('Unexpected state value');
            },
            ''
        );

        return implode('', ['{', PHP_EOL, $result, $indentation, '}',]);
    }

    private function renderIndentation(int $level): string
    {
        return str_repeat('    ', $level);
    }

    private function renderValue($value, int $level): string
    {
        return is_array($value) ? $this->renderArray($value, $level) : $this->renderScalarValue($value);
    }

    private function renderScalarValue($value): string
    {
        if (is_bool($value)) {
            return ($value === true) ? 'true' : 'false';
        }

        return (string) $value;
    }

    private function renderArray(array $data, int $level): string
    {
        $keys = array_keys($data);

        return array_reduce(
            $keys,
            function ($acc, $key) use ($data, $level) {
                $acc = [
                    $acc,
                    '{',
                    PHP_EOL,
                    $this->renderIndentation($level + 2),
                    $key,
                    ': ',
                    $data[$key],
                    PHP_EOL,
                    $this->renderIndentation($level + 1),
                    '}',
                ];

                return implode('', $acc);
            },
            ''
        );
    }
}