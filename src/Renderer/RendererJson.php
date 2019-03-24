<?php


namespace Gendiff\Renderer;


class RendererJson implements Renderer
{
    public function render(array $data): string
    {
        return json_encode($data);
    }
}
