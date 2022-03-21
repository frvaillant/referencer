<?php

namespace App\Controller\Structure;

use App\Entity\Structure;
use App\Form\StructureType;
use App\Repository\StructureRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/structure")
 */
class StructureController extends AbstractController
{



    /**
     * @Route("/", name="structure_index", methods={"GET"})
     */
    public function index(StructureRepository $structureRepository): Response
    {
        return $this->render('structure/index.html.twig', [
            'structure' => $structureRepository->findMyStructure(),
        ]);
    }



    /**
     * @Route("/{id}/edit", name="structure_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Structure $structure, KernelInterface $kernel, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(StructureType::class, $structure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $uploadedFile = $form['imageFile']->getData();
            if ($uploadedFile) {
                list($name, $extension) =  explode('.', $uploadedFile->getClientOriginalName());
                $destination = $kernel->getProjectDir() . '/public/uploads/';
                $newFilename = 'logo.' . $extension;
                $fileUploader->upload($uploadedFile, $newFilename, $destination, $extension, $structure);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('structure_index');
        }

        return $this->render('structure/edit.html.twig', [
            'structure' => $structure,
            'form' => $form->createView(),
        ]);
    }

}
