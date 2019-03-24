<?php


namespace Gendiff\Renderer;


interface Renderer
{
    public function render(array $data): string;
}