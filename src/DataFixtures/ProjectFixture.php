<?php

namespace App\DataFixtures;

use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProjectFixture extends Fixture
{
    const PROJECTS = [
        'Aménagements routiers',
        'Aménagements publics',
        'Carrières',
        'Gestion des milieux',
        'PLU',
    ];

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < count(self::PROJECTS); $i++) {
            $project = new Project();
            $project->setName(self::PROJECTS[$i]);
            $this->addReference('project_' . $i, $project);
            $manager->persist($project);
        }

        $manager->flush();
    }
}
