<?php


namespace Gendiff;


class JSONParser implements Parser
{
    public function parse(string $pathToFile): array
    {
        $data = file_get_contents($pathToFile);
        return json_decode($data, true);
    }
}
