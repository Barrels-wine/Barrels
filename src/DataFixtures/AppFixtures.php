<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Fidry\AliceDataFixtures\Loader\PurgerLoader;

class AppFixtures extends Fixture
{
    public const PATH = [
        'src/DataFixtures/Fixtures/001-users.yml',
        'src/DataFixtures/Fixtures/002-wines.yml',
        'src/DataFixtures/Fixtures/003-bottles.yml',
    ];

    /** @var PurgerLoader */
    private $loader;

    public function __construct(PurgerLoader $loader)
    {
        $this->loader = $loader;
    }

    public function load(ObjectManager $manager)
    {
        $objects = $this->loader->load(self::PATH);

        $manager->flush();
    }
}
