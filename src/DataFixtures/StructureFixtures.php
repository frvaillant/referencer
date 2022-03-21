<?php

namespace App\DataFixtures;

use App\Entity\Structure;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StructureFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $structure = new Structure();
        $structure->setName('Marsilea');
        $structure->setTitle('Bureau d\'études en environnement');
        $structure->setAdress1('12 rue de la solution');
        $structure->setZipCode('33000');
        $structure->setCity('Bordeaux');
        $structure->setPhoneNumber1('06 87 56 45 36');
        $structure->setEmail('marsilea@marsilea.fr');
        $structure->setWebsite('http://marsilea.fr');
        $structure->setSiret('456 7654 67534 543');
        $structure->setLogo('');
        $manager->persist($structure);

        $manager->flush();
    }
}
