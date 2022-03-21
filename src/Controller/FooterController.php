<?php


namespace App\Controller;


use App\Repository\StructureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FooterController extends AbstractController
{

    public function footer(StructureRepository $structureRepository)
    {
        $structure = $structureRepository->findMyStructure();

        return $this->render('footer.html.twig', [
            'structure' => $structure
        ]);
    }

    public function footerLogin()
    {
        return $this->render('footer_login.html.twig', [
        ]);
    }

}
