<?php


namespace Gendiff\Renderer;


use RuntimeException;

class RendererFactory
{
    public static function getRenderer(string $format): Renderer
    {
        switch ($format) {
            case 'pretty':
                return new RendererPretty();

            case 'plain':
                return new RendererPlain();

            case 'json':
                return new RendererJson();
        }

        throw new RuntimeException('Unexpected data format');
    }
}
