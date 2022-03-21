<?php


namespace App\Controller\References;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PdfFooterController extends AbstractController
{
    /**
     * @Route("/footerpdf", name="pdf_footer")
     */
    public function index()
    {
        return $this->render('reference_list/__footer.html.twig');
    }

}
