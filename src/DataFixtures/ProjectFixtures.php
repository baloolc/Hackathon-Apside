<?php

namespace App\DataFixtures;

use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class ProjectFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $nbProject =0;
        for ($i =0; $i < EnterpriseFixtures::AGENCIES_NUMBER; $i++){
            $enterprise = $this->getReference('enterprise_' . $i);

            for ($j = 0; $j < 6; $j++) {
                $project = new Project();
                $project->setName($faker->name());
                $project->setSociety($faker->sentence());
                $project->setDescription($faker->paragraph());
                $project->setEnterprise($enterprise);
                $this->addReference('project_' . $nbProject, $project);
                $nbProject++;
                $manager->persist($project);
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            EnterpriseFixtures::class,
        ];
    }
}
