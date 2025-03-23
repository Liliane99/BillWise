<?php

namespace App\DataFixtures;

use App\Entity\Devis;
use App\Entity\soc\Societe;
use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory as FakerFactory;

class DevisFixtures extends Fixture implements DependentFixtureInterface
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

            for ($j = 0; $j < 5; $j++) { 
                $devis = new Devis();
                $devis->setRefDevis($faker->unique()->ean13)
                      ->setDateDevis(new \DateTime($faker->dateTimeThisDecade->format('Y-m-d H:i:s')))
                      ->setDateEcheance(new \DateTime($faker->dateTimeThisDecade->format('Y-m-d H:i:s')))
                      ->setTitreDevis($faker->sentence)
                      ->setTotalHt($faker->randomFloat(2, 100, 1000))
                      ->setTva(20)
                      ->setTotalTtc($faker->randomFloat(2, 100, 1000))
                      ->setTotalRemise(0)
                      ->setSociety($societe)
                      ->setCreatedBy($createdBy);

                $client = $this->getRandomClient($adminIndex);
                $devis->setClient($client);

                $manager->persist($devis);
                $this->setReference('devis_' . $i, $devis);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            SocieteFixtures::class,
            ClientFixtures::class,
        ];
    }

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    private function getRandomClient(int $adminIndex): Client
    {
        $admin = $this->getReference('admin_' . $adminIndex);
        $clientRepository = $this->entityManager->getRepository(Client::class);
        $clients = $clientRepository->findBy(['creator' => $admin]);
        return $clients[array_rand($clients)];
    }

}
