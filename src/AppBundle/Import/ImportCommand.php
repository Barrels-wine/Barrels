<?php

namespace AppBundle\Import;

use AppBundle\Entity\Bottle;
use AppBundle\Entity\Wine;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Yaml\Yaml;

class ImportCommand extends Command
{
    const DEFAULT_FILE_PATH = __DIR__."/data.csv";
    const DEFAULT_MAPPING_PATH = __DIR__."/mapping.yml";

    protected $entityManager;
    protected $csvParser;

    protected $mapping = [];
    protected $console = null;

    /**
     * ImportCommand constructor.
     * @param $entityManager
     * @param $csvParser
     */
    public function __construct(EntityManager $entityManager, CsvParser $csvParser)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->csvParser = $csvParser;
    }

    protected function configure()
    {
        $this
            ->setName('mycellar:import')
            ->setDescription('Import data from csv file.')
            ->addArgument('path', InputArgument::OPTIONAL, "CSV file path", self::DEFAULT_FILE_PATH)
            ->addArgument('mapping', InputArgument::OPTIONAL, "Mapping file path", self::DEFAULT_MAPPING_PATH)
            ->addOption('purge', null, InputOption::VALUE_NONE, "Purge wine and bottle tables")
            ->addOption('no-interaction', 'n', InputOption::VALUE_NONE, "Do not ask anything")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->console = new SymfonyStyle($input, $output);
        $this->console->title('Cellar data import.');

        if ($input->getOption('purge')) {

            if (!$input->getOption('no-interaction')) {
                if (!$this->console->confirm('Do you really want to purge the database ? All data will be lost forever.', false)) {
                    $this->console->warning('Import cancelled.');
                    return 0;
                }
            }

            $this->console->writeln('Purging cellar data...');

            $connection = $this->entityManager->getConnection();

            $sql = 'DELETE FROM bottle;';
            $stmt = $connection->prepare($sql);
            $stmt->execute();

            $sql = 'DELETE FROM wine;';
            $stmt = $connection->prepare($sql);
            $stmt->execute();

        }

        // Get mapping configuration
        $mappingPath = $input->getArgument('mapping');
        try {
            $this->mapping = Yaml::parse(file_get_contents($mappingPath));
        } catch (ParseException $e) {
            $this->console->error("Impossible de lire le fichier de mapping.");
            $this->console->error($e->getMessage());
            return 1;
        }

        // Import data
        $path = $input->getArgument('path');
        $rows = $this->csvParser->parse($path);

        if (!$rows) {
            $this->console->error('Error while parsing file.  Please check file content.');
            return 1;
        }

        $this->console->writeln('Importing data...');
        $this->console->progressStart(count($rows));

        foreach ($rows as $row) {
            $bottle = new Bottle();
            $bottle = $this->setProperty($bottle, 'acquisitionPrice', $row);
            $bottle = $this->setProperty($bottle, 'estimationPrice', $row);
            $bottle = $this->setProperty($bottle, 'volume', $row);
            $bottle = $this->setProperty($bottle, 'storageLocation', $row);
            $bottle->setWine($this->getOrCreateWine($row));
            $this->entityManager->persist($bottle);
            $this->entityManager->flush();
            $this->console->progressAdvance(1);
        }

        $this->console->progressFinish();
        $this->console->success('Database imported successfully.');
    }

    public function getOrCreateWine($row)
    {
        // First check if wine exist
        // Wine unicity is defined by its name and vintage.
        $wine = $this
            ->entityManager
            ->getRepository('AppBundle:Wine')
            ->findOneBy([
                'name' => $row[$this->mapping['name']],
                'vintage' => $row[$this->mapping['vintage']],
            ])
        ;

        if ($wine) {
            return $wine;
        }

        $wine = new Wine();
        $wine = $this->setProperty($wine, 'name', $row);
        $wine = $this->setProperty($wine, 'designation', $row);
        $wine = $this->setProperty($wine, 'varietal', $row);
        $wine = $this->setProperty($wine, 'color', $row);
        $wine = $this->setProperty($wine, 'vintage', $row);
        $wine = $this->setProperty($wine, 'country', $row);
        $wine = $this->setProperty($wine, 'region', $row);
        $wine = $this->setProperty($wine, 'winemaker', $row);
        $wine = $this->setProperty($wine, 'rating', $row);
        $wine = $this->setProperty($wine, 'comment', $row);
        $wine = $this->setProperty($wine, 'foodPairing', $row);
        $wine = $this->setProperty($wine, 'reference', $row);
        $wine = $this->setProperty($wine, 'classificationLevel', $row);
        $wine = $this->setProperty($wine, 'aging', $row);
        $wine = $this->setProperty($wine, 'bestAging', $row);
        $wine = $this->setProperty($wine, 'bestAfter', $row);
        $wine = $this->setProperty($wine, 'drinkAfter', $row);
        $wine = $this->setProperty($wine, 'alcoholDegree', $row);
        $wine = $this->setProperty($wine, 'temperature', $row);
        $wine = $this->setProperty($wine, 'batch', $row);
        $wine = $this->setProperty($wine, 'category', $row);
        return $wine;
    }

    public function setProperty($object, $property, $row)
    {
        if (!isset($this->mapping[$property]) || !$this->mapping[$property]) {
            $this->console->note('Property '.$property.' is not defined in mapping.');
            return $object;
        }

        $methodName = sprintf('set%s', ucfirst($property));
        if (!method_exists($object, $methodName)) {
            $this->console->warning('Method '.$methodName.' is not defined for object of class '.get_class($object).'.');
            return $object;
        }

        $object->$methodName($row[$this->mapping[$property]]);
        return $object;
    }
}