<?php
declare(strict_types=1);

namespace TestDiffGenerator;


use PHPUnit\Framework\TestCase;
use function Gendiff\genDiff;


class DiffGeneratorTest extends TestCase
{
    public function testGenDiffOfFlatJson(): void
    {
        $diff = genDiff(__DIR__ . '/fixtures/before.json', __DIR__ . '/fixtures/after.json');
        $expected = file_get_contents(__DIR__.'/fixtures/result');
        $this->assertEquals($expected, $diff);
    }

    public function testGenDiffOfFlatYaml(): void
    {
        $diff = genDiff(__DIR__ . '/fixtures/before.yaml', __DIR__ . '/fixtures/after.yaml');
        $expected = file_get_contents(__DIR__.'/fixtures/result');
        $this->assertEquals($expected, $diff);
    }
}
