<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory as FakerFactory;

class UserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = FakerFactory::create('fr_FR');
        $pwd = 'test';

        for ($i = 0; $i < 2; $i++) {
            $admin = new User();
            $admin->setEmail('admin' . $i . '@user.fr')
                  ->setRoles(['ROLE_ADMIN'])
                  ->setName($faker->name)
                  ->setSurname($faker->firstName)
                  ->setProfilePictureName($faker->imageUrl);
            $hashedPassword = $this->passwordHasher->hashPassword($admin, $pwd);
            $admin->setPassword($hashedPassword);
            $admin->setIsVerified(true);
                  
            $manager->persist($admin);
            $this->addReference('admin_' . $i, $admin);

            for ($j = 0; $j < 10; $j++) {
                $user = new User();
                $user->setEmail('user' . $i . '_' . $j . '@user.fr')
                     ->setRoles([$j % 2 == 0 ? 'ROLE_USER' : 'ROLE_COMPTABLE'])
                     ->setName($faker->name)
                     ->setSurname($faker->firstName)
                     ->setProfilePictureName($faker->imageUrl);
                $hashedPassword = $this->passwordHasher->hashPassword($user, $pwd);
                $user->setPassword($hashedPassword);
                $user->setIsVerified(true);
                $manager->persist($user);
                $this->addReference('user_' . $i . '_' . $j, $user);
            }
        }

        $manager->flush();
    }
}