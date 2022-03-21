<?php

namespace App\Controller\Study;

use App\Entity\Study;
use App\Entity\StudyCategory;
use App\Form\StudyType;
use App\Repository\CityRepository;
use App\Repository\StudyCategoryRepository;
use App\Repository\StudyRepository;
use App\Service\FileUploader;
use App\Service\SynthesisDeletor;
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @Route("/study")
 */
class StudyController extends AbstractController
{

    /**
     * @Route("/", name="study_index", methods={"GET"})
     * @param StudyRepository $studyRepository
     * @return Response
     */
    public function index(StudyRepository $studyRepository): Response
    {
        return $this->render('study/index.html.twig', [
            'studies' => $studyRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="study_new", methods={"GET","POST"})
     * @param Request $request
     * @param KernelInterface $kernel
     * @param FileUploader $fileUploader
     * @param CityRepository $cityRepository
     * @param StudyCategoryRepository $categoryRepository
     * @param StudyCategory|null $cat
     * @return Response
     */
    public function new(
        Request $request,
        KernelInterface $kernel,
        FileUploader $fileUploader,
        CityRepository $cityRepository,
        StudyCategoryRepository $categoryRepository,
        SynthesisDeletor $deletor
    ): Response {


        $study = new Study();
        $form = $this->createForm(StudyType::class, $study);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()) {

                if('' !== $request->request->get('list-keywords-added')) {
                    $keywords = $request->request->get('list-keywords-added');
                    $study->setKeywords(explode(',', $keywords));
                }

                $uploadedFile = $form['imageFile']->getData();

                if ($uploadedFile) {
                    list($name, $extension) = explode('.', $uploadedFile->getClientOriginalName());
                    $destination = $kernel->getProjectDir() . '/public/uploads';
                    $newFilename = uniqid() . '.' . $extension;
                    $fileUploader->upload($uploadedFile, $newFilename, $destination, $extension, $study);
                }


                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($study);
                $entityManager->flush();
                $deletor->delete();
                return $this->redirectToRoute('home');

        }

        return $this->render('study/new.html.twig', [
            'study' => $study,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="study_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Study $study, KernelInterface $kernel, FileUploader $fileUploader): Response
    {

        $form = $this->createForm(StudyType::class, $study);
        $cityName = $study->getCity() ? $study->getCity()->getCityName() : '';
        $form->get('cityName')->setData($cityName);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $keywords = $request->request->get('list-keywords-added');

            $study->setKeywords(null);
            if ($keywords !== '') {
                $study->setKeywords(explode(',', $keywords));
            }

            $uploadedFile = $form['imageFile']->getData();

            if ($uploadedFile) {
                $startFile = $kernel->getProjectDir() . '/public/uploads/' . $study->getLogo();
                if (is_file($startFile)) {
                    unlink($startFile);
                }

                list($name, $extension) = explode('.', $uploadedFile->getClientOriginalName());
                $destination = $kernel->getProjectDir() . '/public/uploads';
                $newFilename = uniqid() . '.' . $extension;
                $fileUploader->upload($uploadedFile, $newFilename, $destination, $extension, $study);
            }

            $uploadedFile2 = $form['imageFile2']->getData();

            if ($uploadedFile2) {
                $startFile = $kernel->getProjectDir() . '/public/uploads/' . $study->getLogo2();
                if (is_file($startFile)) {
                    unlink($startFile);
                }

                list($name, $extension) = explode('.', $uploadedFile2->getClientOriginalName());
                $destination = $kernel->getProjectDir() . '/public/uploads';
                $newFilename = uniqid() . '_2.' . $extension;
                $fileUploader->upload($uploadedFile2, $newFilename, $destination, $extension, $study, 'setLogo2');
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('study/edit.html.twig', [
            'study' => $study,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="study_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Study $study, SynthesisDeletor $deletor): Response
    {
        if ($this->isCsrfTokenValid('delete' . $study->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($study);
            $entityManager->flush();
            $deletor->delete();
        }

        return $this->redirectToRoute('study_index');
    }
}
