<?php
declare(strict_types=1);

namespace Gendiff;

use Gendiff\Parser\Parser;
use Gendiff\Renderer\Renderer;

class DiffGenerator
{
    private $astBuilder;
    private $parser;
    private $renderer;

    public function __construct(ASTBuilder $ASTBuilder, Parser $parser, Renderer $renderer)
    {
        $this->astBuilder = $ASTBuilder;
        $this->parser = $parser;
        $this->renderer = $renderer;
    }

    public function generateDiff(string $pathToFile1, string $pathToFile2): string
    {
        $firstDataSet = $this->parser->parse($pathToFile1);
        $secondDataSet = $this->parser->parse($pathToFile2);

        $ast = $this->astBuilder->buildAst($firstDataSet, $secondDataSet);

        return $this->renderer->render($ast);
    }
}
