<?php

namespace App\Test\Controller;

use App\Entity\Devis;
use App\Repository\DevisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DevisControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private DevisRepository $repository;
    private string $path = '/devis/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Devis::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Devi index');

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
            'devi[ref_devis]' => 'Testing',
            'devi[date_devis]' => 'Testing',
            'devi[date_echeance]' => 'Testing',
            'devi[titre_devis]' => 'Testing',
            'devi[total_ht]' => 'Testing',
            'devi[tva]' => 'Testing',
            'devi[total_ttc]' => 'Testing',
            'devi[total_remise]' => 'Testing',
            'devi[created_at]' => 'Testing',
            'devi[updated_at]' => 'Testing',
            'devi[society]' => 'Testing',
            'devi[creator]' => 'Testing',
            'devi[client]' => 'Testing',
        ]);

        self::assertResponseRedirects('/devis/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Devis();
        $fixture->setRef_devis('My Title');
        $fixture->setDate_devis('My Title');
        $fixture->setDate_echeance('My Title');
        $fixture->setTitre_devis('My Title');
        $fixture->setTotal_ht('My Title');
        $fixture->setTva('My Title');
        $fixture->setTotal_ttc('My Title');
        $fixture->setTotal_remise('My Title');
        $fixture->setCreated_at('My Title');
        $fixture->setUpdated_at('My Title');
        $fixture->setSociety('My Title');
        $fixture->setCreator('My Title');
        $fixture->setClient('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Devi');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Devis();
        $fixture->setRef_devis('My Title');
        $fixture->setDate_devis('My Title');
        $fixture->setDate_echeance('My Title');
        $fixture->setTitre_devis('My Title');
        $fixture->setTotal_ht('My Title');
        $fixture->setTva('My Title');
        $fixture->setTotal_ttc('My Title');
        $fixture->setTotal_remise('My Title');
        $fixture->setCreated_at('My Title');
        $fixture->setUpdated_at('My Title');
        $fixture->setSociety('My Title');
        $fixture->setCreator('My Title');
        $fixture->setClient('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'devi[ref_devis]' => 'Something New',
            'devi[date_devis]' => 'Something New',
            'devi[date_echeance]' => 'Something New',
            'devi[titre_devis]' => 'Something New',
            'devi[total_ht]' => 'Something New',
            'devi[tva]' => 'Something New',
            'devi[total_ttc]' => 'Something New',
            'devi[total_remise]' => 'Something New',
            'devi[created_at]' => 'Something New',
            'devi[updated_at]' => 'Something New',
            'devi[society]' => 'Something New',
            'devi[creator]' => 'Something New',
            'devi[client]' => 'Something New',
        ]);

        self::assertResponseRedirects('/devis/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getRef_devis());
        self::assertSame('Something New', $fixture[0]->getDate_devis());
        self::assertSame('Something New', $fixture[0]->getDate_echeance());
        self::assertSame('Something New', $fixture[0]->getTitre_devis());
        self::assertSame('Something New', $fixture[0]->getTotal_ht());
        self::assertSame('Something New', $fixture[0]->getTva());
        self::assertSame('Something New', $fixture[0]->getTotal_ttc());
        self::assertSame('Something New', $fixture[0]->getTotal_remise());
        self::assertSame('Something New', $fixture[0]->getCreated_at());
        self::assertSame('Something New', $fixture[0]->getUpdated_at());
        self::assertSame('Something New', $fixture[0]->getSociety());
        self::assertSame('Something New', $fixture[0]->getCreator());
        self::assertSame('Something New', $fixture[0]->getClient());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Devis();
        $fixture->setRef_devis('My Title');
        $fixture->setDate_devis('My Title');
        $fixture->setDate_echeance('My Title');
        $fixture->setTitre_devis('My Title');
        $fixture->setTotal_ht('My Title');
        $fixture->setTva('My Title');
        $fixture->setTotal_ttc('My Title');
        $fixture->setTotal_remise('My Title');
        $fixture->setCreated_at('My Title');
        $fixture->setUpdated_at('My Title');
        $fixture->setSociety('My Title');
        $fixture->setCreator('My Title');
        $fixture->setClient('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/devis/');
    }
}
