<?php

declare(strict_types=1);

namespace App\Import;

use App\Entity\Bottle;
use App\Entity\Wine;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class Importer
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var Parser */
    protected $parser;

    /** @var array */
    protected $mapping = [];

    /** @var SymfonyStyle */
    protected $console;

    public function __construct(EntityManagerInterface $entityManager, Parser $parser)
    {
        $this->entityManager = $entityManager;
        $this->parser = $parser;
    }

    public function purge()
    {
        $connection = $this->entityManager->getConnection();

        $sql = 'DELETE FROM bottle;';
        $stmt = $connection->prepare($sql);
        $stmt->execute();

        $sql = 'DELETE FROM wine;';
        $stmt = $connection->prepare($sql);
        $stmt->execute();
    }

    public function import(SymfonyStyle $console, string $mappingPath, string $dataPath): int
    {
        $this->console = $console;

        // Get mapping
        if (!$this->getMapping($mappingPath)) {
            return 1;
        }

        // Get data
        $this->console->writeln('Reading data from ' . $dataPath);
        $rows = $this->parser->parse($dataPath);

        if (!$rows) {
            $this->console->error('Error while parsing data file. Please check file content.');

            return 1;
        }

        // Import data
        $bottlesCount = $this->getTotalCount($rows);
        $this->console->writeln('Importing ' . $bottlesCount . ' bottles in database.');
        $this->console->progressStart($bottlesCount);

        $this->importData($rows);

        $this->console->progressFinish();
        $this->console->success('Database imported successfully.');

        return 0;
    }

    public function importData(array $rows)
    {
        foreach ($rows as $row) {
            $wine = $this->getOrCreateWine($row);
            for ($i = 0; $i < $row[$this->mapping['nbBottles']]; ++$i) {
                $bottle = new Bottle();
                $bottle = $this->setProperty($bottle, 'acquisitionPrice', $row, function ($price) { return $this->formatPrice($price); });
                $bottle = $this->setProperty($bottle, 'estimationPrice', $row, function ($price) { return $this->formatPrice($price); });
                $bottle = $this->setProperty($bottle, 'volume', $row, 'string', Bottle::DEFAULT_VOLUME);
                $bottle = $this->setProperty($bottle, 'storageLocation', $row);
                $bottle->setWine($wine);
                $this->entityManager->persist($bottle);
                $this->console->progressAdvance(1);
            }
            $this->entityManager->flush();
        }
    }

    public function getMapping(string $mappingPath)
    {
        try {
            $this->mapping = Yaml::parse(file_get_contents($mappingPath));
        } catch (ParseException $e) {
            $this->console->error('Error while reading mapping file.');
            $this->console->error($e->getMessage());

            return false;
        }

        return true;
    }

    public function getTotalCount(array $rows): int
    {
        $count = 0;
        foreach ($rows as $row) {
            $count += (int) $row[$this->mapping['nbBottles']];
        }

        return $count;
    }

    public function setProperty($object, string $property, array $row, $cast = 'string', $default = null)
    {
        if (!isset($this->mapping[$property]) || !$this->mapping[$property]) {
            $this->console->note('Property ' . $property . ' is not defined in mapping.');

            return $object;
        }

        $setter = sprintf('set%s', ucfirst($property));
        if (!method_exists($object, $setter)) {
            $this->console->warning('Method ' . $setter . ' is not defined for object of class ' . get_class($object) . '.');

            return $object;
        }

        $value = $row[$this->mapping[$property]];
        $value = $value === '0' || $value === '' ? $default : $value;

        if ($value !== null) {
            if (\is_callable($cast)) {
                $value = $cast($value);
            } else {
                settype($value, $cast);
            }
        }

        $object->$setter($value);

        return $object;
    }

    public function formatPrice(string $price): int {
        $price = (float) $price;
        $price = $price * 100;
        $price = (int) round($price);

        return $price;
    }

    public function getOrCreateWine(array $row): Wine
    {
        // First check if wine exist
        // Wine unicity is defined by its name and vintage.
        $wine = $this
            ->entityManager
            ->getRepository(Wine::class)
            ->findOneBy([
                'name' => $row[$this->mapping['name']],
                'vintage' => (int) $row[$this->mapping['vintage']],
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
        $wine = $this->setProperty($wine, 'vintage', $row, 'int');
        $wine = $this->setProperty($wine, 'country', $row);
        $wine = $this->setProperty($wine, 'region', $row);
        $wine = $this->setProperty($wine, 'winemaker', $row);
        $wine = $this->setProperty($wine, 'rating', $row);
        $wine = $this->setProperty($wine, 'comment', $row);
        $wine = $this->setProperty($wine, 'foodPairing', $row);
        $wine = $this->setProperty($wine, 'reference', $row);
        $wine = $this->setProperty($wine, 'classificationLevel', $row);
        $wine = $this->setProperty($wine, 'drinkFrom', $row);
        $wine = $this->setProperty($wine, 'drinkTo', $row);
        $wine = $this->setProperty($wine, 'climaxFrom', $row);
        $wine = $this->setProperty($wine, 'climaxTo', $row);
        $wine = $this->setProperty($wine, 'alcoholDegree', $row, 'float');
        $wine = $this->setProperty($wine, 'temperature', $row, 'int');
        $wine = $this->setProperty($wine, 'batch', $row);
        $wine = $this->setProperty($wine, 'category', $row, 'string', 'Vins');

        return $wine;
    }
}
