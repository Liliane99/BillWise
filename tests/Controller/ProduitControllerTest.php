<?php

namespace App\Test\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProduitControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ProduitRepository $repository;
    private string $path = '/produit/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Produit::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Produit index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'produit[designation]' => 'Testing',
            'produit[nb_apprenant_min]' => 'Testing',
            'produit[nb_apprenant_max]' => 'Testing',
            'produit[price_unit]' => 'Testing',
            'produit[categorie]' => 'Testing',
            'produit[taux_tva]' => 'Testing',
            'produit[duration]' => 'Testing',
            'produit[exigeance]' => 'Testing',
            'produit[certification]' => 'Testing',
            'produit[created_at]' => 'Testing',
            'produit[updated_at]' => 'Testing',
            'produit[society]' => 'Testing',
            'produit[creator]' => 'Testing',
        ]);

        self::assertResponseRedirects('/produit/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Produit();
        $fixture->setDesignation('My Title');
        $fixture->setNb_apprenant_min('My Title');
        $fixture->setNb_apprenant_max('My Title');
        $fixture->setPrice_unit('My Title');
        $fixture->setCategorie('My Title');
        $fixture->setTaux_tva('My Title');
        $fixture->setDuration('My Title');
        $fixture->setExigeance('My Title');
        $fixture->setCertification('My Title');
        $fixture->setCreated_at('My Title');
        $fixture->setUpdated_at('My Title');
        $fixture->setSociety('My Title');
        $fixture->setCreator('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Produit');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Produit();
        $fixture->setDesignation('My Title');
        $fixture->setNb_apprenant_min('My Title');
        $fixture->setNb_apprenant_max('My Title');
        $fixture->setPrice_unit('My Title');
        $fixture->setCategorie('My Title');
        $fixture->setTaux_tva('My Title');
        $fixture->setDuration('My Title');
        $fixture->setExigeance('My Title');
        $fixture->setCertification('My Title');
        $fixture->setCreated_at('My Title');
        $fixture->setUpdated_at('My Title');
        $fixture->setSociety('My Title');
        $fixture->setCreator('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'produit[designation]' => 'Something New',
            'produit[nb_apprenant_min]' => 'Something New',
            'produit[nb_apprenant_max]' => 'Something New',
            'produit[price_unit]' => 'Something New',
            'produit[categorie]' => 'Something New',
            'produit[taux_tva]' => 'Something New',
            'produit[duration]' => 'Something New',
            'produit[exigeance]' => 'Something New',
            'produit[certification]' => 'Something New',
            'produit[created_at]' => 'Something New',
            'produit[updated_at]' => 'Something New',
            'produit[society]' => 'Something New',
            'produit[creator]' => 'Something New',
        ]);

        self::assertResponseRedirects('/produit/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getDesignation());
        self::assertSame('Something New', $fixture[0]->getNb_apprenant_min());
        self::assertSame('Something New', $fixture[0]->getNb_apprenant_max());
        self::assertSame('Something New', $fixture[0]->getPrice_unit());
        self::assertSame('Something New', $fixture[0]->getCategorie());
        self::assertSame('Something New', $fixture[0]->getTaux_tva());
        self::assertSame('Something New', $fixture[0]->getDuration());
        self::assertSame('Something New', $fixture[0]->getExigeance());
        self::assertSame('Something New', $fixture[0]->getCertification());
        self::assertSame('Something New', $fixture[0]->getCreated_at());
        self::assertSame('Something New', $fixture[0]->getUpdated_at());
        self::assertSame('Something New', $fixture[0]->getSociety());
        self::assertSame('Something New', $fixture[0]->getCreator());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Produit();
        $fixture->setDesignation('My Title');
        $fixture->setNb_apprenant_min('My Title');
        $fixture->setNb_apprenant_max('My Title');
        $fixture->setPrice_unit('My Title');
        $fixture->setCategorie('My Title');
        $fixture->setTaux_tva('My Title');
        $fixture->setDuration('My Title');
        $fixture->setExigeance('My Title');
        $fixture->setCertification('My Title');
        $fixture->setCreated_at('My Title');
        $fixture->setUpdated_at('My Title');
        $fixture->setSociety('My Title');
        $fixture->setCreator('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/produit/');
    }
}
