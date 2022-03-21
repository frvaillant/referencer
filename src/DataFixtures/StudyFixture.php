<?php

namespace App\DataFixtures;

use App\Entity\Study;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;
use \DateTime;

class StudyFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 260; $i++) {
            $study = new Study();
            $study->setTitle($faker->sentence(6));
            $study->setCategory($this->getReference('category_' . rand(0,8)));
            $study->setSubCategory($this->getReference('subCategory_' . rand(0,5)));
            $study->setProject($this->getReference('project_' . rand(0,4)));
            $study->setClient($this->getReference('client_' . rand(0,9)));
            $study->setStartDate(new DateTime(rand(2018, 2020) . '-' . rand(1,11) . '-' . rand(1,29)));
            $study->setEndDate(new DateTime('2020-12-30'));
            $study->setEndDate(null);
            $study->setDescription($faker->realText(230));
            $manager->persist($study);
        }

        $manager->flush();
    }

    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return [
            ClientFixture::class,
            StudyCategoryFixtures::class,
            SubCategoryFixture::class,
            ProjectFixture::class
        ];
    }
}
