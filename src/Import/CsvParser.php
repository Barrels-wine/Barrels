<?php

declare(strict_types=1);

namespace App\Import;

class CsvParser
{
    public const DELIMITER = ';';

    public function parse($filename): ?array
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            return null;
        }

        $header = null;
        $data = [];
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 0, self::DELIMITER)) !== false) {
                if (!$header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }

        return $data;
    }
}
