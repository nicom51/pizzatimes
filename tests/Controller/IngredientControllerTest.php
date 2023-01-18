<?php

namespace App\Test\Controller;

use App\Entity\Ingredient;
use App\Repository\IngredientRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

class IngredientControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private IngredientRepository $repository;
    private string $path = '/admin/ingredient/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Ingredient::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('mario@gmail.com');
        $this->client->loginUser($testUser);

        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Sauvegarder', [
            'ingredient[name]' => 'poivre',
            'ingredient[price]' => 50,
        ]);

        self::assertResponseRedirects('/admin/ingredient/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testEdit(): void
    {
        $fixture = new Ingredient();
        $fixture->setName('olive grise');
        $fixture->setPrice(2);

        $this->repository->save($fixture, true);

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('mario@gmail.com');
        $this->client->loginUser($testUser);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Sauvegarder', [
            'ingredient[name]' => 'olive verte',
            'ingredient[price]' => 3,
        ]);

        self::assertResponseRedirects('/admin/ingredient/');

        $fixture = $this->repository->findAll();

        self::assertSame('olive verte', $fixture[0]->getName());
        self::assertSame(300, $fixture[0]->getPrice());
    }
}
