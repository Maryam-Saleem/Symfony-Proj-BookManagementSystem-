<?php

namespace App\DataFixtures;

use App\Factory\BookFactory;
use App\Factory\OrderFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        UserFactory::createOne(['email'=>'h.maryamAdmin@gmail.com', 'roles'=>['ROLE_ADMIN']], );
        UserFactory::createOne(['email'=>'h.maryamUser@gmail.com','roles'=>['ROLE_USER']]);
        UserFactory::createMany(10);
        Bookfactory::createMany(5);
        OrderFactory::createMany(5);
        $manager->flush();
    }
}
