<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\soc\Societe;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;

class ClientFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = FakerFactory::create('fr_FR');

        for ($i = 0; $i < 4; $i++) {
            /** @var Societe $societe */
            $societe = $this->getReference('societe_' . $i);
            $adminIndex = (int) ($i / 2); 
            
            /** @var User $creator */
            $creator = $this->getReference('admin_' . $adminIndex);

            for ($j = 0; $j < 5; $j++) { 
                $client = new Client();
                $client->setNom($faker->lastName)
                       ->setPrenom($faker->firstName)
                       ->setNumTel($faker->unique()->numberBetween(100000000, 999999999))
                       ->setNumFix($faker->unique()->numberBetween(100000000, 999999999))
                       ->setEmail($faker->email)
                       ->setAdresse($faker->streetAddress)
                       ->setCodePostal(94350)
                       ->setVille($faker->city)
                       ->setRaisonSociale($faker->company)
                       ->setNumSiret($faker->unique()->numberBetween(100000000, 999999999))
                       ->setType($faker->randomElement(['entreprise', 'particulier']))
                       ->setSociety($societe)
                       ->setCreator($creator);

                $manager->persist($client);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            SocieteFixtures::class,
        ];
    }
}
