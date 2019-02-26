<?php
declare(strict_types=1);

namespace TestDiffGenerator;


use PHPUnit\Framework\TestCase;
use Gendiff\DiffGenerator;


class DiffGeneratorTest extends TestCase
{
    public function testGenDiff(): void
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
}
