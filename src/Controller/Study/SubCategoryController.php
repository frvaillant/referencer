<?php

namespace App\Controller\Study;

use App\Entity\SubCategory;
use App\Form\SubCategoryType;
use App\Repository\SubCategoryRepository;
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sub/category")
 */
class SubCategoryController extends AbstractController
{
    /**
     * @Route("/", name="sub_category_index")
     */
    public function index(Request $request, DataTableFactory $dataTableFactory, SubCategoryRepository $subCategoryRepository): Response
    {

        $subcategories = $subCategoryRepository->findBy([], [
            'category' => 'ASC',
            'name' => 'ASC'
        ]);

        foreach ($subcategories as $category) {
            $results[] = [
                'refId'    => $category->getId(),
                'name'     => $category->getName(),
                'category' => $category->getCategory()->getName(),
                'refs'     => count($category->getStudies())
            ];
        }

        $datatable = $dataTableFactory->create()
            ->add('refId', TextColumn::class, [
                'label'     => 'ref.',
                'visible'   => false
            ])
            ->add('name', TextColumn::class, [
                'label'     => 'Nom de la sous-catégorie',
            ])
            ->add('category', TextColumn::class, [
                'label'     => 'Catégorie parente',
            ])
            ->add('refs', TextColumn::class, [
                'label'     => 'Nombre de références',
            ]);

        $datatable->createAdapter(ArrayAdapter::class, $results);
        $datatable->handleRequest($request);

        if ($datatable->isCallback()) {
            return $datatable->getResponse();
        }


        return $this->render('sub_category/index.html.twig', [
            'datatable' => $datatable,
        ]);
    }

    /**
     * @Route("/new", name="sub_category_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $subCategory = new SubCategory();
        $form = $this->createForm(SubCategoryType::class, $subCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($subCategory);
            $entityManager->flush();

            return $this->redirectToRoute('sub_category_index');
        }

        return $this->render('sub_category/new.html.twig', [
            'sub_category' => $subCategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sub_category_show", methods={"GET"})
     */
    public function show(SubCategory $subCategory): Response
    {
        return $this->render('sub_category/show.html.twig', [
            'sub_category' => $subCategory,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sub_category_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SubCategory $subCategory): Response
    {
        $form = $this->createForm(SubCategoryType::class, $subCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sub_category_index');
        }

        return $this->render('sub_category/edit.html.twig', [
            'sub_category' => $subCategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sub_category_delete", methods={"DELETE"})
     */
    public function delete(Request $request, SubCategory $subCategory): Response
    {
        if ($this->isCsrfTokenValid('delete'.$subCategory->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($subCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sub_category_index');
    }
}
