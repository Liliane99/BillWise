<?php

namespace App\Test\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClientControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ClientRepository $repository;
    private string $path = '/client/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Client::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Client index');

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
            'client[nom]' => 'Testing',
            'client[prenom]' => 'Testing',
            'client[num_tel]' => 'Testing',
            'client[num_fix]' => 'Testing',
            'client[email]' => 'Testing',
            'client[adresse]' => 'Testing',
            'client[code_postal]' => 'Testing',
            'client[ville]' => 'Testing',
            'client[raison_sociale]' => 'Testing',
            'client[num_siret]' => 'Testing',
            'client[type]' => 'Testing',
            'client[created_at]' => 'Testing',
            'client[updated_at]' => 'Testing',
            'client[society]' => 'Testing',
            'client[creator]' => 'Testing',
        ]);

        self::assertResponseRedirects('/client/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Client();
        $fixture->setNom('My Title');
        $fixture->setPrenom('My Title');
        $fixture->setNum_tel('My Title');
        $fixture->setNum_fix('My Title');
        $fixture->setEmail('My Title');
        $fixture->setAdresse('My Title');
        $fixture->setCode_postal('My Title');
        $fixture->setVille('My Title');
        $fixture->setRaison_sociale('My Title');
        $fixture->setNum_siret('My Title');
        $fixture->setType('My Title');
        $fixture->setCreated_at('My Title');
        $fixture->setUpdated_at('My Title');
        $fixture->setSociety('My Title');
        $fixture->setCreator('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Client');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Client();
        $fixture->setNom('My Title');
        $fixture->setPrenom('My Title');
        $fixture->setNum_tel('My Title');
        $fixture->setNum_fix('My Title');
        $fixture->setEmail('My Title');
        $fixture->setAdresse('My Title');
        $fixture->setCode_postal('My Title');
        $fixture->setVille('My Title');
        $fixture->setRaison_sociale('My Title');
        $fixture->setNum_siret('My Title');
        $fixture->setType('My Title');
        $fixture->setCreated_at('My Title');
        $fixture->setUpdated_at('My Title');
        $fixture->setSociety('My Title');
        $fixture->setCreator('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'client[nom]' => 'Something New',
            'client[prenom]' => 'Something New',
            'client[num_tel]' => 'Something New',
            'client[num_fix]' => 'Something New',
            'client[email]' => 'Something New',
            'client[adresse]' => 'Something New',
            'client[code_postal]' => 'Something New',
            'client[ville]' => 'Something New',
            'client[raison_sociale]' => 'Something New',
            'client[num_siret]' => 'Something New',
            'client[type]' => 'Something New',
            'client[created_at]' => 'Something New',
            'client[updated_at]' => 'Something New',
            'client[society]' => 'Something New',
            'client[creator]' => 'Something New',
        ]);

        self::assertResponseRedirects('/client/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNom());
        self::assertSame('Something New', $fixture[0]->getPrenom());
        self::assertSame('Something New', $fixture[0]->getNum_tel());
        self::assertSame('Something New', $fixture[0]->getNum_fix());
        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getAdresse());
        self::assertSame('Something New', $fixture[0]->getCode_postal());
        self::assertSame('Something New', $fixture[0]->getVille());
        self::assertSame('Something New', $fixture[0]->getRaison_sociale());
        self::assertSame('Something New', $fixture[0]->getNum_siret());
        self::assertSame('Something New', $fixture[0]->getType());
        self::assertSame('Something New', $fixture[0]->getCreated_at());
        self::assertSame('Something New', $fixture[0]->getUpdated_at());
        self::assertSame('Something New', $fixture[0]->getSociety());
        self::assertSame('Something New', $fixture[0]->getCreator());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Client();
        $fixture->setNom('My Title');
        $fixture->setPrenom('My Title');
        $fixture->setNum_tel('My Title');
        $fixture->setNum_fix('My Title');
        $fixture->setEmail('My Title');
        $fixture->setAdresse('My Title');
        $fixture->setCode_postal('My Title');
        $fixture->setVille('My Title');
        $fixture->setRaison_sociale('My Title');
        $fixture->setNum_siret('My Title');
        $fixture->setType('My Title');
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
        self::assertResponseRedirects('/client/');
    }
}
