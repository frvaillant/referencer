<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Client;
use Faker;

class ClientFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i <10; $i++) {
            $client = new Client();
            $client->setName($faker->company);
            $client->setAddress1($faker->address);
            $client->setZipCode($faker->postcode);
            $client->setPhoneNumber1($faker->phoneNumber);
            $client->setCity($faker->city);
            $client->setEmail($faker->email);
            $manager->persist($client);
            $this->addReference('client_' . $i, $client);
        }

        $manager->flush();
    }

}
