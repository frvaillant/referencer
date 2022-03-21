<?php


namespace App\Configurator;


use Symfony\Component\HttpKernel\KernelInterface;

abstract class FileExtractor
{

    /**
     * @var KernelInterface
     */
    protected $kernel;
    /**
     * @var string
     */
    protected $file;

    protected $keys;

    /**
     * @var void
     */
    protected $parts;

    protected $config = [];

    const STARTER = '#REFERENCER CONFIG';
    const CLOSER   = '#END REFERENCER CONFIG';

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        $this->file = $this->kernel->getProjectDir() . '/.env.local';
    }

    protected function getFileContent(): string
    {
        return file_get_contents($this->file);
    }

    protected function getFileParts(): array
    {
        $parts = [];
        $fileElements = explode(self::STARTER . PHP_EOL, $this->getFileContent());
        $parts['start'] = $fileElements[0];
        list($vars, $end) = explode(PHP_EOL . self::CLOSER, $fileElements[1]);
        $parts['vars'] = $vars;
        $parts['end']  = $end;
        return $parts;
    }

    protected function makeVars()
    {
        $vars = $this->parts['vars'];
        $lines = explode(PHP_EOL, $vars);
        foreach ($lines as $line) {
                list($name, $value) = explode('=', $line);
                $this->{$name} = $value;
                $this->keys[] = $name;
                $this->config[$name] = $value;
        }
    }

    protected function makeLine(string $varName, string $value): string
    {
        return $varName . '=' . $value . PHP_EOL;
    }

    protected function makeVarsForEnvFile(): string
    {
        $string = self::STARTER . PHP_EOL;
        foreach ($this->keys as $name) {
            $string.= $this->makeLine($name, $this->{$name});
        }
        $string.= self::CLOSER;

        return $string;
    }

    protected function makeFile(): string
    {
        return $this->parts['start'] . $this->makeVarsForEnvFile() . $this->parts['end'];
    }

    public function saveFile(): void
    {
        file_put_contents($this->file, $this->makeFile());
    }

}
