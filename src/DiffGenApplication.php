<?php

namespace Gendiff;

use Docopt;

class DiffGenApplication
{
    public function run(): void
    {
        $doc = <<<HELP
Generate diff

Usage:
  gendiff (-h|--help)
  gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
  -h --help                     Show this screen
  --format <fmt>                Report format [default: pretty]

HELP;

        $args = Docopt::handle($doc, ['version' => 'Generate diff 0.1.0']);
    }
}
