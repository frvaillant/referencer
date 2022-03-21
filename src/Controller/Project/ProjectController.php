<?php

namespace App\Controller\Project;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project")
 */
class ProjectController extends AbstractController
{
    /**
     * @Route("/", name="project_index")
     */
    public function index(Request $request, DataTableFactory $dataTableFactory, ProjectRepository $projectRepository): Response
    {

        $projects = $projectRepository->findBy([], [
            'name' => 'ASC'
        ]);

        foreach ($projects as $project) {
            $results[] = [
                'refId' => $project->getId(),
                'name' => $project->getName(),
                'refs' => count($project->getStudies())
            ];
        }

        $datatable = $dataTableFactory->create()
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
        $datatable->handleRequest($request);

        if ($datatable->isCallback()) {
            return $datatable->getResponse();
        }

        return $this->render('project/index.html.twig', [
            'datatable' => $datatable,
        ]);
    }

    /**
     * @Route("/new", name="project_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('project_index');
        }

        return $this->render('project/new.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="project_show", methods={"GET"})
     */
    public function show(Project $project): Response
    {
        return $this->render('project/show.html.twig', [
            'project' => $project,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="project_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Project $project): Response
    {
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('project_index');
        }

        return $this->render('project/edit.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="project_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Project $project): Response
    {
        if ($this->isCsrfTokenValid('delete'.$project->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($project);
            $entityManager->flush();
        }

        return $this->redirectToRoute('project_index');
    }
}
