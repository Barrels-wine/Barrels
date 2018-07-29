<?php

declare(strict_types=1);

namespace App\Import;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportCommand extends Command
{
    public const DEFAULT_FILE_PATH = 'import_data/data.csv';
    public const DEFAULT_MAPPING_PATH = 'import_data/mapping.yml';

    /** @var Importer */
    protected $importer;

    /** @var string */
    protected $projectDir;

    public function __construct(Importer $importer, string $projectDir)
    {
        parent::__construct();
        $this->importer = $importer;
        $this->projectDir = $projectDir;
    }

    protected function configure()
    {
        $this
            ->setName('barrels:import')
            ->setDescription('Import data from csv file.')
            ->addArgument('path', InputArgument::OPTIONAL, 'CSV file path relative to project dir', self::DEFAULT_FILE_PATH)
            ->addArgument('mapping', InputArgument::OPTIONAL, 'Mapping file path relative to project dir', self::DEFAULT_MAPPING_PATH)
            ->addOption('purge', null, InputOption::VALUE_NONE, 'Whether or not to purge wine and bottle tables')
            ->addOption('no-interaction', 'n', InputOption::VALUE_NONE, 'Do not ask anything')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $console = new SymfonyStyle($input, $output);
        $console->title('Cellar data import.');

        if ($input->getOption('purge')) {
            if (!$input->getOption('no-interaction')) {
                if (!$console->confirm('Do you really want to purge the database ? All data will be lost forever.', false)) {
                    $console->warning('Import cancelled.');

                    return 0;
                }
            }

            $console->writeln('Purging database…');

            $this->importer->purge();

            $console->writeln('Database cleared.');
        }

        return $this->importer->import(
            $console,
            $this->projectDir . \DIRECTORY_SEPARATOR . $input->getArgument('mapping'),
            $this->projectDir . \DIRECTORY_SEPARATOR . $input->getArgument('path')
        );
    }
}
