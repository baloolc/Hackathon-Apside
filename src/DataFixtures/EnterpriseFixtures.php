<?php

namespace App\DataFixtures;

use App\Entity\Enterprise;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EnterpriseFixtures extends Fixture
{
    public const AGENCIES = [
        ['name' => 'Apside Strasbourg'],
        ['name' => 'Apside Top Orléans'],
        ['name' => 'Apside Top Mans'],
        ['name' => 'Apside Top Tours'],
        ['name' => 'Apside Top Poitier'],
        ['name' => 'Apside Top Bordeaux'],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::AGENCIES as $enterpriseName) {
            $enterprise = new Enterprise();
            $enterprise->setName($enterpriseName['name']);
            $manager->persist($enterprise);
        }
        $manager->flush();
    }
}
