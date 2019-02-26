<?php


namespace Gendiff;


interface Parser
{
    public function parse(string $data): array;
}
