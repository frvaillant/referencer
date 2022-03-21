<?php


namespace App\Service;


use App\Repository\StudyRepository;

class StarsMaker
{

    private $studyReporistory;

    public function __construct(StudyRepository $studyRepository)
    {
        $this->studyReporistory = $studyRepository;
    }

    public function makeStars(string $stars): array
    {
        $stars = explode(',', $stars);

        $results = [];

        foreach ($stars as $star) {
            $results[] = $this->studyReporistory->findOneById($star);
        }
        return $results;
    }

}
