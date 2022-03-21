<?php

namespace App\DataFixtures;

use App\Entity\StudyCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StudyCategoryFixtures extends Fixture
{

    const CATEGORIES = [
        ['Plans de gestion', '#e57373'],
        ['Volets milieux naturels des études d\'impact', '#f06292'],
        ['Inventaire faune / flore', '#80cbc4'],
        ['Etude de faisabilité', '#4dd0e1'],
        ['Etude règlementaire', '#8bc34a'],
        ['Diagnostic zones humides', '#ffca28'],
        ['Dossiers d’incidences NATURA 2000', '#ff8a65'],
        ['Dossiers de demande de dérogation', '#81d4fa'],
        ['Compléments ou reprises de dossiers', '#ce93d8'],
    ];

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < count(self::CATEGORIES); $i++) {
            $category = new StudyCategory();
            $category->setName(self::CATEGORIES[$i][0]);
            $category->setColor(self::CATEGORIES[$i][1]);
            $manager->persist($category);
            $this->addReference('category_' . $i, $category);
        }

        $manager->flush();
    }
}
