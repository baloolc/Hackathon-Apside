<?php

namespace App\DataFixtures;

use App\Entity\Enterprise;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Filesystem\Filesystem;

class EnterpriseFixtures extends Fixture implements DependentFixtureInterface

{
    public const AGENCIES = [
        ['name' => 'Apside Strasbourg'],
        ['name' => 'Apside Top Orléans'],
        ['name' => 'Apside Top Mans'],
        ['name' => 'Apside Top Tours'],
        ['name' => 'Apside Top Poitier'],
        ['name' => 'Apside Top Bordeaux']
    ];

    public const AGENCIES_NUMBER = 6;


    public function load(ObjectManager $manager): void
    {
        $filesystem = new Filesystem();
        $filesystem->remove('public/uploads/enterprise');
        $filesystem->mkdir('public/uploads/enterprise');

        foreach (self::AGENCIES as $key => $enterpriseName) {
            $enterprise = new Enterprise();
            $enterprise->setName($enterpriseName['name']);
            for ($i = 0; $i < self::AGENCIES_NUMBER; $i++) {
                $photo = 'apside' . $i . 'jpg';
            copy('src/DataFixtures/apside.jpg', 'public/uploads/enterprise/' . $photo);
                $enterprise->setPhoto($photo);
            }
            $this->addReference('enterprise_' . $key, $enterprise);
            $enterprise->addProject($this->getReference('project_' . $key));
            $manager->persist($enterprise);
        }
        $manager->flush();
        
    }

    public function getDependencies()
    {
        return [
            ProjectFixtures::class,
        ];
    }
}
