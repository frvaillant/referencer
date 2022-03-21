<?php

namespace App\Controller\Study;

use App\Entity\StudyCategory;
use App\Form\StudyCategoryType;
use App\Repository\StudyCategoryRepository;
use App\Repository\SubCategoryRepository;
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/study/category")
 */
class StudyCategoryController extends AbstractController
{
    /**
     * @Route("/", name="study_category_index")
     * @param Request $request
     * @param DataTableFactory $dataTableFactory
     * @param StudyCategoryRepository $studyCategoryRepository
     * @return Response
     */
    public function index(Request $request, SubCategoryRepository $subCategoryRepository, DataTableFactory $dataTableFactory, StudyCategoryRepository $studyCategoryRepository): Response
    {
        $categories = $studyCategoryRepository->findBy([], [
            'name' => 'ASC'
        ]);

        foreach ($categories as $category) {
            $results[] = [
                'refId' => $category->getId(),
                'name'  => $category->getName(),
                'refs'  => count($category->getStudies())
            ];
        }

        $datatable = $dataTableFactory->create()
            ->setName('sousCat')
            ->add('refId', TextColumn::class, [
                'label'     => 'ref.',
                'visible'   => false
            ])
            ->add('name', TextColumn::class, [
                'label'     => 'Nom de la catégorie',
            ])
            ->add('refs', TextColumn::class, [
                'label'     => 'Nombre de références',
            ]);
        $datatable->createAdapter(ArrayAdapter::class, $results);


        if($datatable->handleRequest($request)->isCallback()) {
           return $datatable->getResponse();
        };



        return $this->render('study_category/index.html.twig', [
            'datatable'  => $datatable,
        ]);
    }

    /**
     * @Route("/new", name="study_category_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $studyCategory = new StudyCategory();
        $form = $this->createForm(StudyCategoryType::class, $studyCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($studyCategory);
            $entityManager->flush();

            return $this->redirectToRoute('study_category_index');
        }

        return $this->render('study_category/new.html.twig', [
            'study_category' => $studyCategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="study_category_show", methods={"GET"})
     */
    public function show(StudyCategory $studyCategory): Response
    {
        return $this->render('study_category/show.html.twig', [
            'study_category' => $studyCategory,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="study_category_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, StudyCategory $studyCategory): Response
    {
        $form = $this->createForm(StudyCategoryType::class, $studyCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('study_category_index');
        }

        return $this->render('study_category/edit.html.twig', [
            'study_category' => $studyCategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="study_category_delete", methods={"DELETE"})
     */
    public function delete(Request $request, StudyCategory $studyCategory): Response
    {
        if ($this->isCsrfTokenValid('delete'.$studyCategory->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($studyCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('study_category_index');
    }
}
