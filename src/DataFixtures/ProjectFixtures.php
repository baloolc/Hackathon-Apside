<?php

namespace App\DataFixtures;

use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProjectFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for ($i = 0; $i < 6; $i++) {

            $project = new Project();
            $project->setName($faker->name());
            $project->setSociety($faker->sentence());
            $project->setDescription($faker->paragraph());
            $this->addReference('project_' . $i, $project);
            $manager->persist($project);
        }

        $manager->flush();
    }
}
