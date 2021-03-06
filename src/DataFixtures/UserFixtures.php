<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public const USER_NUMBER = 6;

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $filesystem = new Filesystem();
        $filesystem->remove('public/uploads/user');
        $filesystem->mkdir('public/uploads/user');

        $admin = new User();
        $admin->setEmail('admin@apside.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setName('Dupont');
        $admin->setFirstname('Sebastien');
        $admin->setPoste('Administrateur');
        $admin->setEnterprise($this->getReference('enterprise_0'));
        $admin->getProjects($this->getReference('project_0'));
        $hashedPassword = $this->passwordHasher->hashPassword(
            $admin,
            'admin'
        );
        $admin->setPassword($hashedPassword);
        $manager->persist($admin);
        $this->addReference($admin->getEmail(), $admin);

        $user = new User();
        $user->setEmail('user@apside.com');
        $user->setRoles(['ROLE_USER']);
        $user->setName('Dupont');
        $user->setFirstname('Sebastien');
        $user->setPoste('utilisateur');
        $avatar = 'avatar' . 'jpg';
        copy('src/DataFixtures/avatar.jpg', 'public/uploads/user/' . $avatar);
        $user->setAvatar($avatar);
        $user->setEnterprise($this->getReference('enterprise_0'));
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            'user'
        );
        $user->setPassword($hashedPassword);
        $manager->persist($user);
        $this->addReference($user->getEmail(), $user);

        $faker = Factory::create();
        for ($i = 0; $i < self::USER_NUMBER; $i++) {
            $user = new User();
            $user->setEmail($faker->email());
            $user->setRoles(['ROLE_USER']);
            $user->setName($faker->lastname());
            $user->setFirstname($faker->firstname());
            $user->setPoste($faker->sentence());
            $user->setTech($faker->sentence());
            $avatar = 'avatar' . $i . 'jpg';
            copy('src/DataFixtures/avatar.jpg', 'public/uploads/user/' . $avatar);
            $user->setAvatar($avatar);
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $faker->sentence(
                    1,
                    true
                )
            );
            $user->setPassword($hashedPassword);
            $user->setEnterprise($this->getReference('enterprise_' . $i));
            for($j =0; $j< 35; $j++){
            $user->addProject($this->getReference('project_' . $j));
            }
            $manager->persist($user);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ProjectFixtures::class,
            EnterpriseFixtures::class,
        ];
    }
}
