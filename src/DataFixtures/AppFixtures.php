<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Ingredient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        /**
         * Users
         */
        $user = new User();
        $user->setFirstname('mario');
        $user->setLastname('romano');
        $user->setEmail('mario@gmail.com');
        $user->setRoles(array('ROLE_ADMIN'));
        $password = $this->hasher->hashPassword($user, 'pizza');
        $user->setPassword($password);
        $manager->persist($user);

        $user = new User();
        $user->setFirstname('luigi');
        $user->setLastname('conti');
        $user->setEmail('luigi@gmail.com');
        $user->setRoles(array('ROLE_USER'));
        $password = $this->hasher->hashPassword($user, 'pizza');
        $user->setPassword($password);
        $manager->persist($user);

        /**
         * Ingredients
         */
        $ingredient = new Ingredient();
        $ingredient->setName('base tomate');
        $ingredient->setPrice(300);
        $manager->persist($ingredient);

        $ingredient = new Ingredient();
        $ingredient->setName('mozza');
        $ingredient->setPrice(250);
        $manager->persist($ingredient);

        $ingredient = new Ingredient();
        $ingredient->setName('reblochon');
        $ingredient->setPrice(200);
        $manager->persist($ingredient);

        $ingredient = new Ingredient();
        $ingredient->setName('gorgonzola');
        $ingredient->setPrice(225);
        $manager->persist($ingredient);

        $ingredient = new Ingredient();
        $ingredient->setName('chèvre');
        $ingredient->setPrice(225);
        $manager->persist($ingredient);

        $ingredient = new Ingredient();
        $ingredient->setName('miel');
        $ingredient->setPrice(300);
        $manager->persist($ingredient);

        $ingredient = new Ingredient();
        $ingredient->setName('champignon');
        $ingredient->setPrice(125);
        $manager->persist($ingredient);

        $ingredient = new Ingredient();
        $ingredient->setName('jambon');
        $ingredient->setPrice(300);
        $manager->persist($ingredient);

        $ingredient = new Ingredient();
        $ingredient->setName('roquette');
        $ingredient->setPrice(150);
        $manager->persist($ingredient);

        $manager->flush();
    }
}
