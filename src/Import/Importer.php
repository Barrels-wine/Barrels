<?php

declare(strict_types=1);

namespace App\Import;

use App\Entity\Bottle;
use App\Entity\Storage;
use App\Entity\Wine;
use App\Reference\Categories;
use App\Reference\Colors;
use App\Reference\Designations;
use App\Reference\Varietals;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class Importer
{
    public const VARIETALS = [
        'Braquet' => Varietals::BRACHETTO,
        'Cabernet-franc' => Varietals::CABERNET_FRANC_N,
        'Cabernet-sauvignon' => Varietals::CABERNET_SAUVIGNON_N,
        'Cabernet Sauvignon' => Varietals::CABERNET_SAUVIGNON_N,
        'Chenin blanc' => Varietals::CHENIN_B,
        'cinsault' => Varietals::CINSAULT,
        'Gewurtztraminer' => Varietals::GEWURZTRAMINER,
        'Grenache Blanc' => Varietals::GRENACHE_BLANC_B,
        'incrocio mazoni' => Varietals::INCROCIO_MANZONI,
        'merlot' => Varietals::MERLOT,
        'Macabeu' => Varietals::MACABEO,
        'Mourvedre' => Varietals::MOURVEDRE,
        'mourvedre' => Varietals::MOURVEDRE,
        'Rousssane' => Varietals::ROUSSANNE,
        'San Giovese' => Varietals::SANGIOVESE,
        'syrah' => Varietals::SYRAH,
    ];

    public const ISO_CODES = [
        'Afrique du Sud' => 'ZA',
        'Australie' => 'AU',
        'Chili' => 'CL',
        'Espagne' => 'ES',
        'France' => 'FR',
        'Hongrie' => 'HU',
        'Italie' => 'IT',
        'Nouvelle Zélande' => 'NZ',
    ];

    public const COLORS = [
        'blanc' => Colors::WHITE,
        'champagne' => Colors::WHITE,
        'rouge' => Colors::RED,
        'rose' => Colors::ROSE,
        'rosé' => Colors::ROSE,
        'liquoreux' => Colors::WHITE,
    ];

    public const REGIONS = [
        'Abruzzo' => 'Abruzzo',
        'Barossa' => 'Barossa',
        'Castiile et Leon' => 'Castiile et Leon',
        'Charentes' => 'Cognac',
        'Vallée de la Loire' => 'Loire',
        'Marlborough' => 'Marlborough',
        'Ombrie' => 'Ombrie',
        'Catalogne' => 'Catalogne',
        'Toscane' => 'Toscane',
        'Piémont' => 'Piémont',
        'Pouilles' => 'Pouilles',
        'Rioja' => 'Rioja',
        'Ribera Del Duero' => 'Ribera Del Duero',
        'Sardaigne' => 'Sardaigne',
        'Sicile' => 'Sicile',
        'Provence' => 'Provence-Corse',
        'Savoie' => 'Savoie-Bugey',
    ];

    public const CATEGORIES = [
        'vins' => Categories::WINE,
        'Vins' => Categories::WINE,
        'Champagnes' => Categories::CHAMPAGNE,
        'Spiritueux' => Categories::SPIRIT,
        'Liquoreux' => Categories::SWEET,
    ];

    public const DESIGNATIONS = [
        'Aloxe-corton' => 'Aloxe-Corton',
        'Alsace edelzwicker"' => 'Alsace',
        'Alsace (gewurztraminer)' => 'Alsace',
        'Alsace (pinot, pinot blanc ou klevner)' => 'Alsace',
        'Alsace (pinot gris)' => 'Alsace',
        'Alsace (pinot noir)' => 'Alsace',
        'Alsace (riesling)' => 'Alsace',
        'Alsace (tokay-pinot gris)' => 'Alsace',
        'Bâtard-montrachet' => 'Bâtard-Montrachet',
        'Chablis premier cru' => 'Chablis',
        'Chambolle-musigny' => 'Chambolle-Musigny',
        'Charmes-chambertin' => 'Charmes-Chambertin',
        'Chassagne-montrachet' => 'Chassagne-Montrachet',
        'Château-grillet' => 'Château-Grillet',
        'Châteauneuf-du-pape' => 'Châteauneuf-du-Pape',
        'Chevalier-montrachet' => 'Chevalier-Montrachet',
        'Chianti Classico' => 'Chianti classico',
        'Clos de tart' => 'Clos de Tart',
        'Corton charlemagne' => 'Corton-Charlemagne',
        'Côte-rôtie' => 'Côte Rôtie',
        'Coteaux du languedoc' => 'Languedoc',
        'Coteaux du languedoc Pic Saint Loup' => 'Languedoc',
        'Côtes de blaye' => 'Côte de Blaye',
        'Côtes de provence' => 'Côtes de Provence',
        'Côtes de toul' => 'Côtes de Toul',
        'Côtes du jura' => 'Côtes du Jura',
        'Côtes du rhône' => 'Côtes du Rhône',
        'Côtes du rhône-villages Cairanne' => 'Côtes du Rhône villages',
        'Côtes du roussillon' => 'Côtes du Roussillon',
        'Côtes du roussillon-villages' => 'Côtes du Roussillon villages',
        'Crozes-hermitage' => 'Crozes-Hermitage',
        'Echézeaux' => 'Échezeaux',
        'Gevrey-chambertin' => 'Gevrey-Chambertin',
        'Haut-médoc' => 'Haut-Médoc',
        'Macon Lugny' => 'Mâcon',
        'Mazoyères-chambertin' => 'Mazoyères-Chambertin',
        'Menetou-salon' => 'Menetou-Salon',
        'Mondeuse' => 'Bugey',
        'Montagne-saint-émilion' => 'Montagne-Saint-Émilion',
        'Montlouis' => 'Montlouis-sur-Loire',
        'Montlouis pétillant' => 'Montlouis-sur-Loire',
        'Montsant' => 'Montsant',
        'Morey saint-denis' => 'Morey-Saint-Denis',
        'Muscadet sèvre-et-maine' => 'Muscadet Sèvre et Maine',
        'Nebbiolo Langhe' => 'Nebbiolo Langhe',
        'Pessac-léognan' => 'Pessac-Léognan',
        'Pouilly-fuissé' => 'Pouilly-Fuissé',
        'Puligny-montrachet' => 'Puligny-Montrachet',
        'Roussette de savoie' => 'Roussette de Savoie',
        'Saint-émilion' => 'Saint-Émilion',
        'Saint-émilion grand cru' => 'Saint-Émilion grand cru',
        'Saint-estèphe' => 'Saint-Estèphe',
        'Saint-chinian' => 'Saint-Chinian',
        'Saint-julien' => 'Saint-Julien',
        'Saint-joseph' => 'Saint-Joseph',
        'Saumur-champigny' => 'Saumur-Champigny',
        'Vins de pays d\'Oc' => 'Pays d\'Oc',
        'Vin de pays de l\'Hérault' => 'Pays d\'Hérault',
        'Vins de pays des coteaux de l\'Ardèche' => 'Ardèche',
        'Vins de pays des cotes catalanes' => 'Côtes catalanes',
        'Vin de pays des cotes de Gascogne' => 'Côtes de Gascogne',
        'Viré-clessé' => 'Viré-Clessé',
        'Vosne-romanée' => 'Vosne-Romanée',
    ];

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

    public function truncate($table)
    {
        $connection = $this->entityManager->getConnection();
        $sql = 'DELETE FROM ' . $table . ';';
        $stmt = $connection->prepare($sql);
        $stmt->execute();
    }

    public function purge()
    {
        $this->truncate('bottle');
        $this->truncate('wine');
        $this->truncate('storage');
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
        $winesCount = \count($rows) - 1;
        $bottlesCount = $this->getTotalCount($rows);
        $this->console->writeln('Importing ' . $bottlesCount . ' bottles from ' . $winesCount . ' wines in database.');
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
                $bottle = $this->setProperty($bottle, 'storageLocation', $row, function ($location) { return $this->formatStorageLocation($location); });
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
                $value = $cast($value, $object);
            } else {
                settype($value, $cast);
            }
        }

        $object->$setter($value);

        return $object;
    }

    public function formatPrice(string $price): int
    {
        $price = (float) $price;
        $price = $price * 100;
        $price = (int) round($price);

        return $price;
    }

    public function formatStorageLocation(?string $location): ?Storage
    {
        if (!$location || \trim($location) === '') {
            return null;
        }

        $storage = new Storage();
        $storage->setName($location);

        return $storage;
    }

    public function formatDesignation(string $designation = null, Wine $wine): ?string
    {
        if ($wine->getCountry() === 'FR') {
            $references = Designations::getByCountryAndRegion($wine->getCountry(), $wine->getRegion());
        } else {
            $references = Designations::getByCountry($wine->getCountry());
        }

        if (!\in_array($designation, $references, true)) {
            if (!\array_key_exists($designation, self::DESIGNATIONS)) {
                $this->console->warning('Designation ' . $designation . ' is unknown (' . $wine->getCountry() . ' ' . $wine->getRegion() . ')');

                return $designation;
            }

            return self::DESIGNATIONS[$designation];
        }

        return $designation;
    }

    public function formatVarietals(string $varietalsStr): array
    {
        $varietals = str_replace(['.', ',', '+'], ';', $varietalsStr);
        $varietals = str_replace(' ;', ';', $varietals);
        $varietals = str_replace('; ', ';', $varietals);
        $varietals = explode(';', $varietals);

        $cleaned = [];

        foreach ($varietals as $varietal) {
            if (!\in_array($varietal, Varietals::getConstants(), true)) {
                if (!\array_key_exists($varietal, self::VARIETALS)) {
                    $this->console->warning('Varietal ' . $varietal . ' is unknown');
                    continue;
                }
                $cleaned[] = self::VARIETALS[$varietal];
            } else {
                $cleaned[] = $varietal;
            }
        }

        return $cleaned;
    }

    public function formatColor($color)
    {
        $color = mb_strtolower($color);
        if (\in_array($color, \array_keys(self::COLORS), true)) {
            return self::COLORS[$color];
        }

        $this->console->warning('Color ' . $color . ' is unknown');
    }

    public function formatCountry(string $country): string
    {
        return self::ISO_CODES[$country];
    }

    public function formatRegion(string $region): string
    {
        if (!\in_array($region, Designations::getFrenchRegions(), true)) {
            if (!\array_key_exists($region, self::REGIONS)) {
                $this->console->warning('Region ' . $region . ' is unknown');

                return $region;
            }

            return self::REGIONS[$region];
        }

        return $region;
    }

    public function formatCategory(string $category): string
    {
        if (!\in_array($category, Categories::getConstants(), true)) {
            if (!\array_key_exists($category, self::CATEGORIES)) {
                $this->console->warning('Category ' . $category . ' is unknown');

                return Categories::WINE;
            }

            return self::CATEGORIES[$category];
        }

        return $category;
    }

    public function getOrCreateWine(array $row): Wine
    {
        // First check if wine exist
        // Wine unicity is defined by name, designation, vintage, classificationLevel, batch and winemaker
        $wine = $this
            ->entityManager
            ->getRepository(Wine::class)
            ->findOneBy([
                'name' => $row[$this->mapping['name']],
                'designation' => $row[$this->mapping['designation']],
                'vintage' => (int) $row[$this->mapping['vintage']],
                'classificationLevel' => $row[$this->mapping['classificationLevel']],
                'batch' => $row[$this->mapping['batch']],
                'winemaker' => $row[$this->mapping['winemaker']],
            ])
        ;

        if ($wine) {
            return $wine;
        }

        $wine = new Wine();
        $wine = $this->setProperty($wine, 'name', $row);
        $wine = $this->setProperty($wine, 'country', $row, function ($country) { return $this->formatCountry($country); });
        $wine = $this->setProperty($wine, 'region', $row, function ($region) { return $this->formatRegion($region); });
        $wine = $this->setProperty($wine, 'designation', $row, function ($designation, $wine) { return $this->formatDesignation($designation, $wine); });
        $wine = $this->setProperty($wine, 'varietals', $row, function ($varietals) { return $this->formatVarietals($varietals); });
        $wine = $this->setProperty($wine, 'color', $row, function ($color) { return $this->formatColor($color); });
        $wine = $this->setProperty($wine, 'vintage', $row, 'int');
        $wine = $this->setProperty($wine, 'winemaker', $row);
        $wine = $this->setProperty($wine, 'rating', $row, 'int');
        $wine = $this->setProperty($wine, 'comment', $row);
        //$wine = $this->setProperty($wine, 'foodPairing', $row);
        $wine = $this->setProperty($wine, 'classificationLevel', $row);
        $wine = $this->setProperty($wine, 'drinkFrom', $row, 'int');
        $wine = $this->setProperty($wine, 'drinkTo', $row, 'int');
        $wine = $this->setProperty($wine, 'climaxFrom', $row, 'int');
        $wine = $this->setProperty($wine, 'climaxTo', $row, 'int');
        $wine = $this->setProperty($wine, 'alcoholDegree', $row, 'float');
        $wine = $this->setProperty($wine, 'temperature', $row, 'int');
        $wine = $this->setProperty($wine, 'batch', $row);
        $wine = $this->setProperty($wine, 'category', $row, function ($category) { return $this->formatCategory($category); });
        $this->entityManager->persist($wine);

        return $wine;
    }
}
