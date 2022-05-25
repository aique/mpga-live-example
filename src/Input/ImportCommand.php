<?php

namespace App\Input;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends Command
{
    protected static $defaultName = 'app:import-categories';

    private string $filePath;
    private CsvImporter $importer;

    public function __construct(
        CsvImporter $importer,
        string $filePath = ''
    ) {
        $this->importer = $importer;
        $this->filePath = $filePath;

        parent::__construct();
    }

    protected function configure(): void {
        $this
            ->addArgument(
                'filePath',
                InputArgument::REQUIRED,
                'CSV import file path'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        $this->filePath = $input->getArgument('filePath');

        $this->importer->import(
            $this->filePath
        );

        return Command::SUCCESS;
    }
}