<?php

namespace App\Input;

class CsvFakerGenerator
{
    const DEFAULT_NUM_ITEMS = 500;

    public function generate(string $filePath, int $numItems = self::DEFAULT_NUM_ITEMS): void {
        $writer = fopen($filePath, 'w');

        for ($i = 0 ; $i < $numItems ; $i++) {
            fwrite(
                $writer,
                sprintf("Category number %d\n", ($i + 1))
            );
        }

        fclose($writer);
    }
}