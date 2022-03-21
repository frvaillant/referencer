<?php


namespace App\Service;


use Symfony\Component\HttpKernel\KernelInterface;

class SynthesisDeletor
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    const FILE = '/public/uploads/synthese.png';

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function delete()
    {
        if (is_file($this->kernel->getProjectDir() . self::FILE)) {
            $file = $this->kernel->getProjectDir() . self::FILE;
            unlink($file);
        }
    }

}
