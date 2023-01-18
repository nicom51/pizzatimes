<?php

namespace App\Test\Controller;

use App\Entity\Pizza;
use App\Repository\PizzaRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PizzaControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private PizzaRepository $repository;
    private string $path = '/admin/pizza/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Pizza::class);

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

        $uploadedFile = new UploadedFile(
            __DIR__.'/../fixtures/pizza.jpg',
            'pizza.jpg'
        );

        $this->client->submitForm('Ajouter', [
            'pizza[name]' => 'regina',
            'pizza[photo]' => $uploadedFile,
        ]);

        self::assertResponseRedirects('/admin/pizza/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testEdit(): void
    {
        $fixture = new Pizza();
        $fixture->setName('calzone');
        $fixture->setPhoto('calzone.png');

        $this->repository->save($fixture, true);


        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('mario@gmail.com');
        $this->client->loginUser($testUser);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $uploadedFile = new UploadedFile(
            __DIR__.'/../fixtures/pizza.jpg',
            'pizza.jpg'
        );

        $this->client->submitForm('Sauvegarder', [
            'pizza[name]' => 'calzone new',
            'pizza[photo]' => $uploadedFile,
        ]);

        self::assertResponseRedirects('/admin/pizza/');

        $fixture = $this->repository->findAll();

        self::assertSame('calzone new', $fixture[0]->getName());
    }
}
