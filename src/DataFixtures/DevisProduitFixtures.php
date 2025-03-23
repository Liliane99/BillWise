<?php

namespace App\DataFixtures;

use App\Entity\DevisProduit;
use App\Entity\Devis;
use App\Entity\Produit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory as FakerFactory;

class DevisProduitFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = FakerFactory::create('fr_FR');

        for ($i = 0; $i < 4; $i++) {
            /** @var Devis $devis */
            $devis = $this->getReference('devis_' . $i);
            $adminIndex = (int) ($i / 2); 

            for ($j = 0; $j < 10; $j++) { 
                $devisProduit = new DevisProduit();
                $devisProduit->setDevis($devis)
                            ->setProduct($this->getRandomProduct($adminIndex))
                            ->setNbApprenant($faker->numberBetween(1, 50))
                            ->setMontantHt($faker->randomFloat(2, 10, 100))
                            ->setTaxeTva(20)
                            ->setMontantRemise(0);

                $manager->persist($devisProduit);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            DevisFixtures::class,
            ProduitFixtures::class,
        ];
    }

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    private function getRandomProduct(int $adminIndex): Produit
    {
        $admin = $this->getReference('admin_' . $adminIndex);
        $produitRepository = $this->entityManager->getRepository(Produit::class);
        $produits = $produitRepository->findBy(['createdBy' => $admin]);
        return $produits[array_rand($produits)];
    }
}
