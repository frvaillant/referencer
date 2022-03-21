<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VisioController extends AbstractController
{
    /**
     * @Route("/visio", name="visio")
     */
    public function index(): Response
    {
        return $this->render('visio/index.html.twig', [
            'controller_name' => 'VisioController',
        ]);
    }
}
