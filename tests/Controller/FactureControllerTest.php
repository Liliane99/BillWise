<?php

namespace App\Test\Controller;

use App\Entity\Facture;
use App\Repository\FactureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FactureControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private FactureRepository $repository;
    private string $path = '/facture/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Facture::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Facture index');

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
            'facture[ref_facture]' => 'Testing',
            'facture[date_facture]' => 'Testing',
            'facture[titre_facture]' => 'Testing',
            'facture[total_ht]' => 'Testing',
            'facture[tva]' => 'Testing',
            'facture[total_ttc]' => 'Testing',
            'facture[total_remise]' => 'Testing',
            'facture[condition_termes]' => 'Testing',
            'facture[condition]' => 'Testing',
            'facture[date_echeance]' => 'Testing',
            'facture[created_at]' => 'Testing',
            'facture[updated_at]' => 'Testing',
            'facture[society]' => 'Testing',
            'facture[client]' => 'Testing',
            'facture[creator]' => 'Testing',
        ]);

        self::assertResponseRedirects('/facture/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Facture();
        $fixture->setRef_facture('My Title');
        $fixture->setDate_facture('My Title');
        $fixture->setTitre_facture('My Title');
        $fixture->setTotal_ht('My Title');
        $fixture->setTva('My Title');
        $fixture->setTotal_ttc('My Title');
        $fixture->setTotal_remise('My Title');
        $fixture->setCondition_termes('My Title');
        $fixture->setCondition('My Title');
        $fixture->setDate_echeance('My Title');
        $fixture->setCreated_at('My Title');
        $fixture->setUpdated_at('My Title');
        $fixture->setSociety('My Title');
        $fixture->setClient('My Title');
        $fixture->setCreator('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Facture');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Facture();
        $fixture->setRef_facture('My Title');
        $fixture->setDate_facture('My Title');
        $fixture->setTitre_facture('My Title');
        $fixture->setTotal_ht('My Title');
        $fixture->setTva('My Title');
        $fixture->setTotal_ttc('My Title');
        $fixture->setTotal_remise('My Title');
        $fixture->setCondition_termes('My Title');
        $fixture->setCondition('My Title');
        $fixture->setDate_echeance('My Title');
        $fixture->setCreated_at('My Title');
        $fixture->setUpdated_at('My Title');
        $fixture->setSociety('My Title');
        $fixture->setClient('My Title');
        $fixture->setCreator('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'facture[ref_facture]' => 'Something New',
            'facture[date_facture]' => 'Something New',
            'facture[titre_facture]' => 'Something New',
            'facture[total_ht]' => 'Something New',
            'facture[tva]' => 'Something New',
            'facture[total_ttc]' => 'Something New',
            'facture[total_remise]' => 'Something New',
            'facture[condition_termes]' => 'Something New',
            'facture[condition]' => 'Something New',
            'facture[date_echeance]' => 'Something New',
            'facture[created_at]' => 'Something New',
            'facture[updated_at]' => 'Something New',
            'facture[society]' => 'Something New',
            'facture[client]' => 'Something New',
            'facture[creator]' => 'Something New',
        ]);

        self::assertResponseRedirects('/facture/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getRef_facture());
        self::assertSame('Something New', $fixture[0]->getDate_facture());
        self::assertSame('Something New', $fixture[0]->getTitre_facture());
        self::assertSame('Something New', $fixture[0]->getTotal_ht());
        self::assertSame('Something New', $fixture[0]->getTva());
        self::assertSame('Something New', $fixture[0]->getTotal_ttc());
        self::assertSame('Something New', $fixture[0]->getTotal_remise());
        self::assertSame('Something New', $fixture[0]->getCondition_termes());
        self::assertSame('Something New', $fixture[0]->getCondition());
        self::assertSame('Something New', $fixture[0]->getDate_echeance());
        self::assertSame('Something New', $fixture[0]->getCreated_at());
        self::assertSame('Something New', $fixture[0]->getUpdated_at());
        self::assertSame('Something New', $fixture[0]->getSociety());
        self::assertSame('Something New', $fixture[0]->getClient());
        self::assertSame('Something New', $fixture[0]->getCreator());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Facture();
        $fixture->setRef_facture('My Title');
        $fixture->setDate_facture('My Title');
        $fixture->setTitre_facture('My Title');
        $fixture->setTotal_ht('My Title');
        $fixture->setTva('My Title');
        $fixture->setTotal_ttc('My Title');
        $fixture->setTotal_remise('My Title');
        $fixture->setCondition_termes('My Title');
        $fixture->setCondition('My Title');
        $fixture->setDate_echeance('My Title');
        $fixture->setCreated_at('My Title');
        $fixture->setUpdated_at('My Title');
        $fixture->setSociety('My Title');
        $fixture->setClient('My Title');
        $fixture->setCreator('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/facture/');
    }
}
