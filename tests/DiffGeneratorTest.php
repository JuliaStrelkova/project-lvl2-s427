<?php
declare(strict_types=1);

namespace TestDiffGenerator;


use PHPUnit\Framework\TestCase;
use function Gendiff\genDiff;


class DiffGeneratorTest extends TestCase
{
    public function testGenDiffOfFlatPrettyJson(): void
    {
        $diff = genDiff(__DIR__ . '/fixtures/before.json', __DIR__ . '/fixtures/after.json', 'pretty');
        $expected = file_get_contents(__DIR__ . '/fixtures/resultPretty');
        $this->assertEquals($expected, $diff);
    }

    public function testGenDiffOfFlatPrettyYaml(): void
    {
        $diff = genDiff(__DIR__ . '/fixtures/before.yaml', __DIR__ . '/fixtures/after.yaml', 'pretty');
        $expected = file_get_contents(__DIR__ . '/fixtures/resultPretty');
        $this->assertEquals($expected, $diff);
    }

    public function testGenDiffOfFlatNestedPrettyJson(): void
    {
        $diff = genDiff(__DIR__ . '/fixtures/nestedBefore.json', __DIR__ . '/fixtures/nestedAfter.json', 'pretty');
        $expected = file_get_contents(__DIR__ . '/fixtures/nestedResultPretty');
        $this->assertEquals($expected, $diff);
    }

    public function testGenDiffOfFlatNestedPrettyYaml(): void
    {
        $diff = genDiff(__DIR__ . '/fixtures/nestedBefore.yaml', __DIR__ . '/fixtures/nestedAfter.yaml', 'pretty');
        $expected = file_get_contents(__DIR__ . '/fixtures/nestedResultPretty');
        $this->assertEquals($expected, $diff);
    }

//    public function testGenDiffOfFlatNestedPlainJson(): void
//    {
//        $diff = genDiff(__DIR__ . '/fixtures/nestedBefore.json', __DIR__ . '/fixtures/nestedAfter.json', 'plain');
//        $expected = file_get_contents(__DIR__ . '/fixtures/nestedResultPlain');
//        $this->assertEquals($expected, $diff);
//    }
}
