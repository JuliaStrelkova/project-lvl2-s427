<?php
declare(strict_types=1);

namespace TestDiffGenerator;


use PHPUnit\Framework\TestCase;
use Gendiff\DiffGenerator;


class DiffGeneratorTest extends TestCase
{
    public function testGenDiffOfFlatJson(): void
    {
        $diffGenerator = new DiffGenerator();
        $diff = $diffGenerator->genDiff(__DIR__ . '/fixtures/before.json', __DIR__ . '/fixtures/after.json');
        $expected = <<<JSON
{
    host: hexlet.io
  + timeout: 20
  - timeout: 50
  - ip: 127.0.0.1
  - proxy: 123.234.53.22
  + verbose: true
}
JSON;
        $this->assertEquals($expected, $diff);
    }

    public function testGenDiffOfFlatYaml(): void
    {
        $diffGenerator = new DiffGenerator();
        $diff = $diffGenerator->genDiff(__DIR__ . '/fixtures/before.yaml', __DIR__ . '/fixtures/after.yaml');
        $expected = <<<YAML
{
    host: hexlet.io
  + timeout: 20
  - timeout: 50
  - ip: 127.0.0.1
  - proxy: 123.234.53.22
  + verbose: true
}
YAML;
        $this->assertEquals($expected, $diff);
    }
}
