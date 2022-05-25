<?php

namespace App\Input;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

class CsvImporter
{
    const BULK_SIZE = 50;

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function import(string $filePath): void {
        if (!file_exists($filePath)) {
            throw new \Exception('Input file not exists');
        }

        $reader = fopen($filePath, 'r');

        if ($reader === false) {
            throw new \Exception('Input file could not be opened');
        }

        $this->importCategories($reader);

        fclose($reader);
    }

    private function importCategories($reader): void {
        $i = 0;

        while(($line = fgetcsv($reader)) !== false) {
            $category = $this->parseCategory($line);
            $this->entityManager->persist($category);
            $i++;

            if ($i === self::BULK_SIZE) {
                $this->entityManager->flush();
                $this->entityManager->clear();
                $i = 0;
            }
        }
    }

    private function parseCategory(array $line): Category {
        return new Category(
            'test', true
        );
    }
}