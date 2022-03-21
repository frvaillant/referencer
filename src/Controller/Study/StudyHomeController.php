<?php


namespace App\Controller\Study;


use App\Repository\StudyRepository;
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class StudyHomeController extends AbstractController
{
    /**
     * @Route("/references", name="home")
     * @IsGranted("ROLE_USER")
     */
    public function listReferences(Request $request, DataTableFactory $dataTableFactory, StudyRepository $studyRepository)
    {
        $references = $studyRepository->findBy([], [
            'category'  => 'ASC',
            'startDate' => 'DESC',
            'title'     => 'ASC',
        ]);

        foreach ($references as $reference) {
            $keywords = '';
            if (!is_null($reference->getKeywords())) {
                $keywords = implode(',', $reference->getKeywords());
            }
            $results[] = [
                'refId'    => $reference->getId(),
                'name'     => $reference->getTitle(),
                'keywords' => $keywords,
                'category' => $reference->getCategory()->getName(),
                'client'   => $reference->getClient()->getName(),
                'year'     => $reference->getStartDate()->format('Y'),
                'city'     => $reference->getCity() ? $reference->getCity()->getCityName() : '',
            ];
        }

        $datatable = $dataTableFactory->create()
            ->add('refId', TextColumn::class, [
                'label'     => 'ref.',
                'visible'   => false
            ])
            ->add('name', TextColumn::class, [
                'label'     => 'Titre de l\'étude',
                'orderable' => true
            ])
            ->add('category', TextColumn::class, [
                'label'     => 'Catégorie',
                'orderable' => true
            ])
            ->add('client', TextColumn::class, [
                'label'     => 'Client',
                'orderable' => true
            ])
            ->add('keywords', TextColumn::class, [
                'label'     => 'Mots clés',
                'visible'   => false,
                'searchable' => true
            ])
            ->add('city', TextColumn::class, [
                'label'     => 'ville',
                'orderable' => true
            ])
            ->add('year', TextColumn::class, [
                'label'     => 'Année',
                'orderable' => true
            ]);

        $datatable->createAdapter(ArrayAdapter::class, $results);
        $datatable->handleRequest($request);
        if ($datatable->isCallback()) {
            return $datatable->getResponse();
        }

        return $this->render('home/index.html.twig', [
            'datatable' => $datatable,
        ]);
    }


}
