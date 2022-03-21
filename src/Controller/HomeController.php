<?php

namespace App\Controller;

use App\Configurator\ConfVerificator;
use App\Configurator\Env;
use App\Repository\CityRepository;
use App\Repository\ClientRepository;
use App\Repository\ProjectRepository;
use App\Repository\StudyCategoryRepository;
use App\Repository\StudyRepository;
use App\Service\Colors;
use MapUx\Builder\MapBuilder;
use MapUx\Model\Icon;
use MapUx\Model\Marker;
use MapUx\Model\Popup;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class HomeController extends AbstractController
{

    /**
     * @Route("/", name="start", methods={"GET"})
     */
    public function startPage()
    {
        return $this->redirectToRoute('home_base');

    }
    /**
     * @Route("/board", name="home_base", methods={"GET"}, options={"expose"=true})
     * @IsGranted("ROLE_USER")
     */
    public function home(
        StudyRepository $studyRepository,
        StudyCategoryRepository $studyCategoryRepository,
        ProjectRepository $projectRepository,
        ClientRepository $clientRepository,
        KernelInterface $kernel,
        ChartBuilderInterface $chartBuilder,
        CityRepository $cityRepository,
        Env $env
    ): Response {

        /*
        $allStudies = $studyRepository->findAll();
        $manager = $this->getDoctrine()->getManager();
        foreach ($allStudies as $study) {
            $study->setCity($cityRepository->getRandomCity());
            $manager->persist($study);
        }
        $manager->flush();
        */

        $countEntities = [
            'study'    => $studyRepository->countStudies()['total'],
            'category' => $studyCategoryRepository->countCategories()['total'],
            'project'  => $projectRepository->countProjects()['total'],
            'client'   => $clientRepository->countClients()['total']
        ];

        $chart = $chartBuilder->createChart(Chart::TYPE_PIE);

        $studies = $studyRepository->findAllGroupByCat();
        $labels = [];
        $data = [];
        $colors = [];
        foreach ($studies as $study) {
            $labels[] = $study['name'];
            $data[] = $study['total'];
            $colors[] = 'rgb(' . rand(0, 255) . ',' . rand(0, 255) . ',' . rand(0, 255) . ')';
        }

        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'études',
                    'backgroundColor' => $colors,
                    'borderColor' => 'none',
                    'data' => $data
                ]
            ]
        ]);
        $chart->setOptions([
            'legend' => [
                'display' => false,
            ]
        ]);

        $chartp = $chartBuilder->createChart(Chart::TYPE_PIE);
        $studies = $studyRepository->findAllGroupByProject();
        $labels = [];
        $data = [];
        $colors = [];
        foreach ($studies as $study) {
            $labels[] = $study['name'];
            $data[] = $study['total'];
            $colors[] = 'rgb(' . rand(0, 255) . ',' . rand(0, 255) . ',' . rand(0, 255) . ')';
        }

        $chartp->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'études',
                    'backgroundColor' => $colors,
                    'borderColor' => 'none',
                    'data' => $data
                ]
            ]
        ]);
        $chartp->setOptions([
            'legend' => [
                'display' => false,
            ]
        ]);

        $chartc = $chartBuilder->createChart(Chart::TYPE_PIE);
        $studies = $studyRepository->findAllGroupByClient();
        $labels = [];
        $data = [];
        $colors = [];
        foreach ($studies as $study) {
            $labels[] = $study['name'];
            $data[] = $study['total'];
            $matColors = Colors::COLORS;
            shuffle($matColors);
            $colors[] = $matColors[0];
        }

        $chartc->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'études',
                    'backgroundColor' => $colors,
                    'borderColor' => 'none',
                    'data' => $data
                ]
            ]
        ]);
        $chartc->setOptions([
            'legend' => [
                'display' => false,
            ]
        ]);



        $lastStudies  = $studyRepository->lastStudies(3);

        $referenceFile = $kernel->getProjectDir() . '/public/pdf/references.pdf';
        $hasReferenceFile = false;
        $fileCreationDate = new \DateTime();

        if (is_file($referenceFile)) {
            $hasReferenceFile = true;
            $fileCreationDate->setTimestamp(filemtime($referenceFile));
        }

        return $this->render('home/home.html.twig', [
                'count'              => $countEntities,
                'hasReferenceFile'   => $hasReferenceFile,
                'file_creation_date' => $fileCreationDate,
                'last_studies'       => $lastStudies,
                'chart'              => $chart,
                'chartp'             => $chartp,
                'chartc'             => $chartc,
                'config'             => ConfVerificator::isDefault($env->getConfig())
        ]);
    }
}
