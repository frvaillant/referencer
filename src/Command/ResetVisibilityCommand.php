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

class ResetVisibilityCommand extends Command
{

    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, StudyRepository $studyRepository)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->studyRepository = $studyRepository;
    }

    protected function configure()
    {
        $this->setName('reset:visibility');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->writeln('<fg=white;bg=green>**** START ****</>');

        $studies = $this->studyRepository->findAll();

        foreach ($studies as $study) {
            $study->setHideClient(0);
            $this->entityManager->persist($study);
        }

        $this->entityManager->flush();
        $io->writeln('<fg=white;bg=green>**** Ended ****</>');
    }
}
