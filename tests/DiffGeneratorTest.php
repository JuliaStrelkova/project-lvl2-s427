<?php
declare(strict_types=1);

namespace TestDiffGenerator;


use Gendiff\ASTBuilder;
use Gendiff\DiffGenerator;
use Gendiff\Parser\Parser;
use Gendiff\Renderer\RendererFactory;
use PHPUnit\Framework\TestCase;


class DiffGeneratorTest extends TestCase
{
    public function testGenDiffOfFlatJsonToPrettyFormat(): void
    {
        $diffGenerator = new DiffGenerator(
            new ASTBuilder(),
            new Parser(),
            RendererFactory::getRenderer('pretty')
        );

        $diff = $diffGenerator->generateDiff(
            __DIR__ . '/fixtures/before.json',
            __DIR__ . '/fixtures/after.json'
        );
        $expected = file_get_contents(__DIR__ . '/fixtures/resultPretty');
        $this->assertEquals($expected, $diff);
    }

    public function testGenDiffOfFlatYamlToPrettyFormat(): void
    {
        $diffGenerator = new DiffGenerator(
            new ASTBuilder(),
            new Parser(),
            RendererFactory::getRenderer('pretty')
        );

        $diff = $diffGenerator->generateDiff(
            __DIR__ . '/fixtures/before.yaml',
            __DIR__ . '/fixtures/after.yaml'
        );
        $expected = file_get_contents(__DIR__ . '/fixtures/resultPretty');
        $this->assertEquals($expected, $diff);
    }

    public function testGenDiffOfNestedJsonToPrettyFormat(): void
    {
        $diffGenerator = new DiffGenerator(
            new ASTBuilder(),
            new Parser(),
            RendererFactory::getRenderer('pretty')
        );

        $diff = $diffGenerator->generateDiff(
            __DIR__ . '/fixtures/nestedBefore.json',
            __DIR__ . '/fixtures/nestedAfter.json'
        );
        $expected = file_get_contents(__DIR__ . '/fixtures/nestedResultPretty');
        $this->assertEquals($expected, $diff);
    }

    public function testGenDiffOfNestedYamlToPrettyFormat(): void
    {

        $diffGenerator = new DiffGenerator(
            new ASTBuilder(),
            new Parser(),
            RendererFactory::getRenderer('pretty')
        );

        $diff = $diffGenerator->generateDiff(
            __DIR__ . '/fixtures/nestedBefore.yaml',
            __DIR__ . '/fixtures/nestedAfter.yaml'
        );
        $expected = file_get_contents(__DIR__ . '/fixtures/nestedResultPretty');
        $this->assertEquals($expected, $diff);
    }

    public function testGenDiffOfNestedJsonToPlainFormat(): void
    {
        $diffGenerator = new DiffGenerator(
            new ASTBuilder(),
            new Parser(),
            RendererFactory::getRenderer('plain')
        );

        $diff = $diffGenerator->generateDiff(
            __DIR__ . '/fixtures/nestedBefore.json',
            __DIR__ . '/fixtures/nestedAfter.json'
        );
        $expected = file_get_contents(__DIR__ . '/fixtures/nestedResultPlain');
        $this->assertEquals($expected, $diff);
    }

    public function testGenDiffOfNestedYamlToPlainFormat(): void
    {
        $diffGenerator = new DiffGenerator(
            new ASTBuilder(),
            new Parser(),
            RendererFactory::getRenderer('plain')
        );

        $diff = $diffGenerator->generateDiff(
            __DIR__ . '/fixtures/nestedBefore.yaml',
            __DIR__ . '/fixtures/nestedAfter.yaml'
        );
        $expected = file_get_contents(__DIR__ . '/fixtures/nestedResultPlain');
        $this->assertEquals($expected, $diff);
    }

    public function testGenDiffOfNestedJsonToJsonFormat(): void
    {
        $diffGenerator = new DiffGenerator(
            new ASTBuilder(),
            new Parser(),
            RendererFactory::getRenderer('json')
        );

        $diff = $diffGenerator->generateDiff(
            __DIR__ . '/fixtures/nestedBefore.json',
            __DIR__ . '/fixtures/nestedAfter.json'
        );
        $expected = file_get_contents(__DIR__ . '/fixtures/nestedResult.json');
        $this->assertEquals($expected, $diff);
    }

    public function testGenDiffOfNestedYamlToJsonFormat(): void
    {
        $diffGenerator = new DiffGenerator(
            new ASTBuilder(),
            new Parser(),
            RendererFactory::getRenderer('json')
        );

        $diff = $diffGenerator->generateDiff(
            __DIR__ . '/fixtures/nestedBefore.yaml',
            __DIR__ . '/fixtures/nestedAfter.yaml'
        );
        $expected = file_get_contents(__DIR__ . '/fixtures/nestedResult.json');
        $this->assertEquals($expected, $diff);
    }
}
