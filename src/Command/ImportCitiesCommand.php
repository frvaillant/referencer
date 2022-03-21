<?php


namespace App\Command;


use App\Entity\City;
use App\Repository\CityRepository;
use App\Repository\StudyRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Console\Helper\ProgressBar;

class ImportCitiesCommand extends Command
{

    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(KernelInterface $kernel, EntityManagerInterface $entityManager, CityRepository $cityRepository, StudyRepository $studyRepository)
    {
        parent::__construct();
        $this->kernel = $kernel;
        $this->entityManager = $entityManager;
        $this->cityRepository = $cityRepository;
        $this->studyRepository = $studyRepository;
    }

    protected function configure()
    {
        $this->setName('import:cities');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->writeln('<fg=white;bg=green>**** START ****</>');

        $io->writeln('<info>loading file</info>');
        $file = $this->kernel->getProjectDir() . '/src/Import/cities.xlsx';
        $reader = new Xlsx();
        $spreadsheet = $reader->load($file);
        $io->writeln('<info>get active sheet</info>');
        $workSheet = $spreadsheet->getActiveSheet();
        $i = 1;
        $progressBar = new ProgressBar($output, $workSheet->getHighestRow());

        $io->writeln('<info>Documents creation</info>');
        $progressBar->start();
        while ($i <= $workSheet->getHighestRow()) {
            $city = new City();
            $city
                ->setZipCode($workSheet->getCell('C' . $i)->getValue())
                ->setCityName($workSheet->getCell('B' . $i)->getValue())
                ->setCodeInsee($workSheet->getCell('A' . $i)->getValue())
                ->setLatitude($workSheet->getCell('D' . $i)->getValue())
                ->setLongitude($workSheet->getCell('E' . $i)->getValue());
            $this->entityManager->persist($city);
            $progressBar->advance();
            $i++;
        }
        $progressBar->finish();
        $io->writeln('<info>Cities inserted</info>');

        $io->writeln('<info>Mise à jour des études</info>');

        $studies = $this->studyRepository->findAll();

        foreach ($studies as $study) {
            $city = $this->cityRepository->getRandomCity();
            $study->setCity($city);
            $this->entityManager->persist($study);
        }

        $this->entityManager->flush();
        $io->writeln('<fg=white;bg=green>**** Ended ****</>');
    }
}
