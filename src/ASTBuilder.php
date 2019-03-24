<?php
declare(strict_types=1);

namespace Gendiff;

class ASTBuilder
{
    public const DELETED = 'deleted';
    public const ADDED = 'added';
    public const CHANGED = 'changed';
    public const UNCHANGED = 'unchanged';
    public const NESTED = 'nested';

    public function buildAst(array $firstDataSet, array $secondDataSet): array
    {
        $keysFirstAndSecondDataSet = $this->getUniqueKeys($firstDataSet, $secondDataSet);

        return array_map(
            function (string $key) use ($firstDataSet, $secondDataSet) {

                if (array_key_exists($key, $firstDataSet) && array_key_exists($key, $secondDataSet)) {
                    if (is_array($firstDataSet[$key]) && is_array($secondDataSet[$key])) {
                        return $this->buildNode(
                            self::NESTED,
                            $key,
                            null,
                            null,
                            $this->buildAst($firstDataSet[$key], $secondDataSet[$key])
                        );
                    }

                    if ($firstDataSet[$key] === $secondDataSet[$key]) {
                        return $this->buildNode(self::UNCHANGED, $key, $firstDataSet[$key]);
                    }

                    return $this->buildNode(self::CHANGED, $key, $firstDataSet[$key], $secondDataSet[$key]);
                }

                if (!array_key_exists($key, $secondDataSet)) {
                    return $this->buildNode(self::DELETED, $key, $firstDataSet[$key]);
                }

                return $this->buildNode(self::ADDED, $key, null, $secondDataSet[$key]);
            },
            $keysFirstAndSecondDataSet
        );
    }

    private function buildNode(string $type, string $key, $oldValue = null, $newValue = null, $children = null): array
    {
        return [
            'type' => $type,
            'key' => $key,
            'newValue' => $newValue,
            'oldValue' => $oldValue,
            'children' => $children,
        ];
    }

    private function getUniqueKeys(array $firstDataSet, array $secondDataSet): array
    {
        return array_values(
            array_unique(
                array_merge(array_keys($firstDataSet), array_keys($secondDataSet))
            )
        );
    }
}
