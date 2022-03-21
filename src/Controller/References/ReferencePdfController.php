<?php

namespace App\Controller\References;


use App\Repository\StudyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class ReferencePdfController extends AbstractController
{
    /**
     * @Route("/dossiers", name="references")
     */
    public function index(StudyRepository $studyRepository, KernelInterface $kernel)
    {

        $okForMap = is_file($kernel->getProjectDir() . '/public/uploads/synthese.png');

        if(!is_dir($kernel->getProjectDir() . '/public/pdf')) {
            mkdir($kernel->getProjectDir() . '/public/pdf');
        }
        $studies = $studyRepository->findBy([], [
            'title' => 'ASC'
        ]);

        return $this->render('reference_list/index.html.twig', [
            'studies' => $studies,
            'okformap' => $okForMap
        ]);
    }
}
