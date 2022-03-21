<?php


namespace App\Configurator;


use Symfony\Component\HttpKernel\KernelInterface;

class Env extends FileExtractor
{
    protected $keys = [];

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        $this->file = $this->kernel->getProjectDir() . '/.env.local';
        $this->parts = $this->getFileParts();
        $this->makeVars();
    }


    public function get($var)
    {
        return $this->{$var};
    }

    public function set($var, $value)
    {
        $this->{$var} = trim($value);

        if(!in_array($var, $this->keys)) {
            $this->keys[] = $var;
        }
    }

    public function getConfig()
    {
        return $this->config;
    }

}
