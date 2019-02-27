<?php
declare(strict_types=1);

namespace Gendiff;


function parseJson(string $pathToFile): array
{
    $data = file_get_contents($pathToFile);

    return json_decode($data, true);
}
