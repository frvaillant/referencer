<?php

namespace App\Controller\Equipment;

use App\Entity\Equipment;
use App\Form\EquipmentType;
use App\Repository\EquipmentRepository;
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/equipment")
 */
class EquipmentController extends AbstractController
{
    /**
     * @Route("/", name="equipment_index", methods={"GET", "POST"})
     * @param Request $request
     * @param DataTableFactory $dataTableFactory
     * @param EquipmentRepository $equipmentRepository
     * @return Response
     */
    public function index(Request $request, DataTableFactory $dataTableFactory, EquipmentRepository $equipmentRepository): Response
    {
        $results = [];
        $equipments = $equipmentRepository->findAll();

        foreach ($equipments as $equipment) {
            $results[] = [
                'refId' => $equipment->getId(),
                'name'  => $equipment->getName(),
                'brand' => $equipment->getBrand(),
                'model' => $equipment->getModel(),
                'quantity' => $equipment->getQuantity(),
            ];
        }

        $datatable = $dataTableFactory->create()
            ->add('refId', TextColumn::class, [
                'label'     => 'ref.',
                'visible'   => false
            ])
            ->add('name', TextColumn::class, [
                'label'     => 'Nom',
                'orderable' => true
            ])
            ->add('brand', TextColumn::class, [
                'label'     => 'Marque',
                'orderable' => true
            ])
            ->add('model', TextColumn::class, [
                'label'     => 'Modèle',
            ])
            ->add('quantity', TextColumn::class, [
                'label'     => 'Quantité',
            ]);


        $datatable->createAdapter(ArrayAdapter::class, $results);
        $datatable->handleRequest($request);

        if ($datatable->isCallback()) {
            return $datatable->getResponse();
        }

        return $this->render('equipment/index.html.twig', [
            'datatable' => $datatable,
        ]);
    }

    /**
     * @Route("/new", name="equipment_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $equipment = new Equipment();
        $form = $this->createForm(EquipmentType::class, $equipment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($equipment);
            $entityManager->flush();

            return $this->redirectToRoute('equipment_index');
        }

        return $this->render('equipment/new.html.twig', [
            'equipment' => $equipment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="equipment_show", methods={"GET"})
     */
    public function show(Equipment $equipment): Response
    {
        return $this->render('equipment/show.html.twig', [
            'equipment' => $equipment,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="equipment_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Equipment $equipment): Response
    {
        $form = $this->createForm(EquipmentType::class, $equipment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'donnée mise à jour');
            return $this->redirectToRoute('equipment_index');
        }

        return $this->render('equipment/edit.html.twig', [
            'equipment' => $equipment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="equipment_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Equipment $equipment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$equipment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($equipment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('equipment_index');
    }
}
