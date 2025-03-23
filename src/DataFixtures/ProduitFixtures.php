<?php

namespace App\DataFixtures;

use App\Entity\Produit;
use App\Entity\soc\Societe;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;

class ProduitFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = FakerFactory::create('fr_FR');

        for ($i = 0; $i < 4; $i++) {
            /** @var Societe $societe */
            $societe = $this->getReference('societe_' . $i);
            $adminIndex = (int) ($i / 2); 

            /** @var User $createdBy */
            $createdBy = $this->getReference('admin_' . $adminIndex);

            for ($j = 0; $j < 10; $j++) { 
                $produit = new Produit();
                $produit->setDesignation($faker->words(3, true))
                        ->setPriceUnit($faker->randomFloat(2, 10, 100))
                        ->setCategorie($faker->randomElement(['hybride', 'distanciel', 'présentiel']))
                        ->setTauxTva(20)
                        ->setSociety($societe)
                        ->setCreatedBy($createdBy);

                $manager->persist($produit);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            SocieteFixtures::class,
        ];
    }
}
