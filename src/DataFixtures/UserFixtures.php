<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $user = new User();
        $user
            ->setUsername('usuario')
            ->setPassword('$argon2id$v=19$m=65536,t=4,p=1$E9yNfMMiT9OI5R6ee+l0yg$7GvMzpN8aw1OZhYi587hi83aOvtjPE4F/5fA5H19McQ');

        $manager->persist($user);
        $manager->flush();
    }
}
