<?php


namespace App\Controller\AjaxController;

use App\Entity\Project;
use App\Repository\ClientRepository;
use App\Repository\EquipmentRepository;
use App\Repository\ProjectRepository;
use App\Repository\StructureRepository;
use App\Repository\StudyCategoryRepository;
use App\Repository\StudyRepository;
use App\Repository\SubCategoryRepository;
use App\Service\ExcelServices\EntityExport;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class ExportToExcelController extends AbstractController
{
    /**
     * @Route("/savetoexcel", name="save_to_excel", methods={"GET"})
     *
     */
    public function download(
        StudyRepository $studyRepository,
        StudyCategoryRepository $studyCategoryRepository,
        SubCategoryRepository $subCategoryRepository,
        ProjectRepository $projectRepository,
        ClientRepository $clientRepository,
        EquipmentRepository $equipmentRepository,
        KernelInterface $kernel,
        StructureRepository $structureRepository
    )
    {
        $exporter = new EntityExport();
        $response = new JsonResponse();
        $excel = new Spreadsheet();

        $structure = $structureRepository->findMyStructure();

        //PROJETS
        $projectFields = [
            'id' => 'getId',
            'nom' => 'getName',
        ];
        $exporter->makeEntitySheet($projectFields, $excel, $projectRepository, 'Types de projets');

        //Catégories
        $catFields = [
            'id' => 'getId',
            'nom' => 'getName',
        ];
        $exporter->makeEntitySheet($catFields, $excel, $studyCategoryRepository, 'Catégories');

        //Sous-Catégories
        $subCatFields = [
            'id' => 'getId',
            'nom' => 'getName',
            'catégorie' => 'getCategory'
        ];
        $exporter->makeEntitySheet($subCatFields, $excel, $subCategoryRepository, 'Sous Catégories');

        //Clients
        $clientFields = [
            'id' => 'getId',
            'nom' => 'getName',
            'Adresse' => 'getAddress1',
            'Complément d\'adresse' => 'getAddress2',
            'Code postal' => 'getZipCode',
            'Ville' => 'getCity',
            'Téléphone 1' => 'getPhoneNumber1',
            'Téléphone 2' => 'getPhoneNumber2',
            'Email' => 'getEmail'
        ];
        $exporter->makeEntitySheet($clientFields, $excel, $clientRepository, 'Clients');

        //ETUDES
        $studyFields = [
            'id' => 'getId',
            'Titre' => 'getTitle',
            'Type de projet' => 'getProject',
            'Catégorie' => 'getCategory',
            'Sous catégories' => 'getSubCategoriesAsText',
            'Client' => 'getClient',
            'Maître d\'ouvrage' => 'getProjectManager',
            'Commune' => 'getCity',
            'Début le' => 'getStartDate',
            'fin le' => 'getEndDate',
            'Description' => 'getDescription',
            'Description complémentaire' => 'getAdditionalDescription',
            'Mots clés' => 'getKeywordsAsString'
        ];
        $exporter->makeEntitySheet($studyFields, $excel, $studyRepository, 'Références', 'title', 'ASC');

        $exporter->style($excel);

        $sheetIndex = $excel->getIndex(
            $excel->getSheetByName('Worksheet')
        );
        $excel->removeSheetByIndex($sheetIndex);

        $writer = new Xlsx($excel);
        $writer->save($kernel->getProjectDir() . '/public/xls/references' . $structure->getName() . '.xlsx');
        $response->setStatusCode(Response::HTTP_OK);
        $response->setData([
            'file' => '/xls/references' . $structure->getName() . '.xlsx',
            ]);

        return $response;
    }

}
