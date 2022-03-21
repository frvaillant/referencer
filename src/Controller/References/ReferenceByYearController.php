<?php


namespace App\Controller\References;


use App\Repository\EquipmentRepository;
use App\Repository\StructureRepository;
use App\Repository\StudyRepository;
use App\Service\Pdf\PdfGenerator;
use App\Service\StarsMaker;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Snappy\Pdf;

class ReferenceByYearController extends AbstractController
{
    /**
     * @Route("/references/pdf/year/{stars}/{desc}/{limitDate}/{clients}/{cats}/{stat}/{equipment}", name="reference_list_year_pdf", methods={"POST"})
     * @param StudyRepository $studyRepository
     * @param StructureRepository $structureRepository
     * @param EquipmentRepository $equipmentRepository
     * @param KernelInterface $kernel
     * @param Pdf $pdf
     * @param StarsMaker $starsMaker
     * @param string|null $stars
     * @param string|null $desc
     * @param string|null $limitDate
     * @param string|null $clients
     * @param string|null $cats
     * @param string|null $stat
     * @param string|null $equipment
     * @return JsonResponse
     */
    public function pdf(
        StudyRepository $studyRepository,
        StructureRepository $structureRepository,
        EquipmentRepository $equipmentRepository,
        KernelInterface $kernel,
        Pdf $pdf,
        StarsMaker $starsMaker,
        string $stars = null,
        string $desc = null,
        string $limitDate = null,
        string $clients = null,
        string $cats = null,
        string $stat = null,
        string $equipment = null
    ) {

        $description = true;
        if ($desc) {
            $description = ($desc === 'false') ? false : true;
        }
        if ($clients) {
            $clients = ($clients === 'false') ? false : true;
        }
        if ($cats) {
            $cats = ($cats === 'false') ? false : true;
        }
        if ($stat) {
            $stat = ($stat === 'false') ? false : true;
        }
        if ($equipment) {
            $equipment = ($equipment === 'false') ? false : true;
        }

        $starsList = [];
        if ($stars && $stars != 'null') {
            $starsList = $starsMaker->makeStars($stars);
        }

        $equipments = null;

        if ($equipment) {
            $equipments = $equipmentRepository->findAll();
        }

        $structure  = $structureRepository->findMyStructure();

        if ($limitDate != 'null') {
            $references = $studyRepository->recentStudies($limitDate);
        } else {
            $references = $studyRepository->findBy([], [
                'startDate' => 'DESC',
                'category'  => 'ASC',
                'title'     => 'ASC'
            ]);
        }

        $folder = $kernel->getProjectDir() . '/public/pdf';
        $file = $folder . '/references.pdf';
        if (is_file($file)) {
            unlink($file);
        }

        $html = $this->renderView('reference_list/index_year_pdf.html.twig', [
            'references'   => $references,
            'structure'    => $structure,
            'stars'        => $starsList,
            'show_desc'    => $description,
            'show_clients' => $clients,
            'show_cats'    => $cats,
            'stat'         => $stat,
            'equipments'   => $equipments
        ]);

        $footer = $this->renderView('reference_list/__footer.html.twig');

        $pdf = PdfGenerator::makeOptions($pdf, $folder, $footer);

        $pdf->generateFromHtml($html, $kernel->getProjectDir() . '/public/pdf/references.pdf');

        $response = new JsonResponse();
        $response->setStatusCode(Response::HTTP_NOT_FOUND);
        if (is_file($file)) {
            $response->setStatusCode(Response::HTTP_OK);
        }
        return $response;
    }

}
