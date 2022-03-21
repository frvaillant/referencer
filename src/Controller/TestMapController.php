<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use App\Repository\StructureRepository;
use App\Repository\StudyCategoryRepository;
use App\Repository\StudyRepository;
use App\Service\StarsMaker;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class TestMapController extends AbstractController
{
    /**
     * @Route("/test/map", name="test_map")
     */
    public function index(
        Request $request,
        StudyRepository $studyRepository,
        StructureRepository $structureRepository,
        KernelInterface $kernel,
        Pdf $pdf,
        StarsMaker $starsMaker,
        StudyCategoryRepository $studyCategoryRepository,
        ProjectRepository $projectRepository,
        ChartBuilderInterface $chartBuilder
    ) {

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
                    'borderColor' => 'white',
                    'borderWidth' => 3,
                    'data' => $data
                ]
            ]
        ]);
        $chart->setOptions([
            'aspectRatio' => 1,
            'responsive' => true,
            'tooltip' => 'enabled',
            'legend' => [
                'display' => true,
                'position' => 'bottom',
                'align' => 'center'
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
                    'borderColor' => 'white',
                    'borderWidth' => 3,
                    'data' => $data
                ]
            ]
        ]);
        $chartp->setOptions([
            'aspectRatio' => 1.2,
            'responsive' => true,
            'legend' => [
                'display' => true,
                'position' => 'bottom',
                'align' => 'center'
            ],
            'layout' => [
                'padding' => [
                    'left'=>'600px']
            ]
        ]);

        return $this->render('test_map/index.html.twig', [
            'chart'  => $chart,
            'chartp' => $chartp,
            'structure' =>$structureRepository->findMyStructure()
        ]);
    }

    /**
    * @Route("/mapto/pdf", name="map_to_pdf")
    */
    public function renderPdf(StructureRepository $structureRepository)
    {

        return $this->render('test_map/map_and_graph.html.twig', [
            'structure' =>$structureRepository->findMyStructure()
        ]);

    }
}
