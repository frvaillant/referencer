<?php

namespace App\DataFixtures;

use App\Entity\SubCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SubCategoryFixture extends Fixture implements DependentFixtureInterface
{

    const SUBCATEGORIES = [
        'Inventaire faune',
        'Inventaire flore',
        'Déterminations d\'échantillons',
        'Etude globale',
        'Plan de gestion',
        'Incidence loi sur l\'eau',
    ];

    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < count(self::SUBCATEGORIES); $i++) {
            $subCategory = new SubCategory();
            $subCategory->setName(self::SUBCATEGORIES[$i]);
            $subCategory->setCategory($this->getReference('category_' . rand(0,8)));
            $this->addReference('subCategory_' . $i, $subCategory);
            $manager->persist($subCategory);
        }

        $manager->flush();
    }

    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return [
            StudyCategoryFixtures::class
        ];
    }
}
