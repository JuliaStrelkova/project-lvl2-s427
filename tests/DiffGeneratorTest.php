<?php
declare(strict_types=1);

namespace TestDiffGenerator;


use PHPUnit\Framework\TestCase;
use function Gendiff\genDiff;


class DiffGeneratorTest extends TestCase
{
    public function testGenDiffOfFlatJsonToPrettyFormat(): void
    {
        $diff = genDiff(__DIR__ . '/fixtures/before.json', __DIR__ . '/fixtures/after.json', 'pretty');
        $expected = file_get_contents(__DIR__ . '/fixtures/resultPretty');
        $this->assertEquals($expected, $diff);
    }

    public function testGenDiffOfFlatYamlToPrettyFormat(): void
    {
        $diff = genDiff(__DIR__ . '/fixtures/before.yaml', __DIR__ . '/fixtures/after.yaml', 'pretty');
        $expected = file_get_contents(__DIR__ . '/fixtures/resultPretty');
        $this->assertEquals($expected, $diff);
    }

    public function testGenDiffOfNestedJsonToPrettyFormat(): void
    {
        $diff = genDiff(__DIR__ . '/fixtures/nestedBefore.json', __DIR__ . '/fixtures/nestedAfter.json', 'pretty');
        $expected = file_get_contents(__DIR__ . '/fixtures/nestedResultPretty');
        $this->assertEquals($expected, $diff);
    }

    public function testGenDiffOfNestedYamlToPrettyFormat(): void
    {
        $diff = genDiff(__DIR__ . '/fixtures/nestedBefore.yaml', __DIR__ . '/fixtures/nestedAfter.yaml', 'pretty');
        $expected = file_get_contents(__DIR__ . '/fixtures/nestedResultPretty');
        $this->assertEquals($expected, $diff);
    }

    public function testGenDiffOfNestedJsonToPlainFormat(): void
    {
        $diff = genDiff(__DIR__ . '/fixtures/nestedBefore.json', __DIR__ . '/fixtures/nestedAfter.json', 'plain');
        $expected = file_get_contents(__DIR__ . '/fixtures/nestedResultPlain');
        $this->assertEquals($expected, $diff);
    }

    public function testGenDiffOfNestedYamlToPlainFormat(): void
    {
        $diff = genDiff(__DIR__ . '/fixtures/nestedBefore.yaml', __DIR__ . '/fixtures/nestedAfter.yaml', 'plain');
        $expected = file_get_contents(__DIR__ . '/fixtures/nestedResultPlain');
        $this->assertEquals($expected, $diff);
    }

    public function testGenDiffOfNestedJsonToJsonFormat(): void
    {
        $diff = genDiff(__DIR__ . '/fixtures/nestedBefore.json', __DIR__ . '/fixtures/nestedAfter.json', 'json');
        $expected = file_get_contents(__DIR__ . '/fixtures/nestedResult.json');
        $this->assertEquals($expected, $diff);
    }

    public function testGenDiffOfNestedYamlToJsonFormat(): void
    {
        $diff = genDiff(__DIR__ . '/fixtures/nestedBefore.yaml', __DIR__ . '/fixtures/nestedAfter.yaml', 'json');
        $expected = file_get_contents(__DIR__ . '/fixtures/nestedResult.json');
        $this->assertEquals($expected, $diff);
    }
}
