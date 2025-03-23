<?php

namespace App\DataFixtures;

use App\Entity\soc\Societe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;

class SocieteFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = FakerFactory::create('fr_FR');

        for ($i = 0; $i < 4; $i++) {
            $societe = new Societe();
            $societe->setRaisonSociale('Société ' . $i)
                    ->setNumSiret($faker->unique()->numberBetween(100000000, 999999999))
                    ->setAdresse($faker->streetAddress)
                    ->setCodePostal(94350)
                    ->setVille($faker->city)
                    ->setNumTel($faker->unique()->numberBetween(100000000, 999999999))
                    ->setNumFix($faker->unique()->numberBetween(100000000, 999999999))
                    ->setAvatarLogo('logo_societe_' . $i . '.png');
            $adminIndex = (int) ($i / 2);
            $admin = $this->getReference('admin_' . $adminIndex);
                
            $societe->setCreatedBy($admin);
            $manager->persist($societe);
            $this->addReference('societe_' . $i, $societe);

            $adminIndex = (int) ($i / 2);
            $userOffset = ($i % 2) * 5; 

            $admin = $this->getReference('admin_' . $adminIndex);
            $societe->addUser($admin);

            for ($j = 0; $j < 5; $j++) {
                $user = $this->getReference('user_' . $adminIndex . '_' . ($userOffset + $j));
                $societe->addUser($user);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}