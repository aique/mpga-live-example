<?php

namespace App\Input;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FakerGeneratorCommand extends Command
{
    protected static $defaultName = 'app:categories-csv-faker';

    private string $filePath;
    private CsvFakerGenerator $faker;

    public function __construct(
        CsvFakerGenerator $faker,
        string $filePath = ''
    ) {
        $this->faker = $faker;
        $this->filePath = $filePath;

        parent::__construct();
    }

    protected function configure(): void {
        $this
            ->addArgument(
                'filePath',
                InputArgument::REQUIRED,
                'CSV export file path'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        $this->faker->generate(
            $input->getArgument('filePath')
        );

        return Command::SUCCESS;
    }
}