<?php


namespace App\Configurator;


use App\Entity\Config;
use App\Repository\ConfigRepository;
use App\Configurator\Env;

class EnvInjector
{
    /**
     * @var Config
     */
    private $userConfig;
    /**
     * @var \App\Configurator\Env
     */
    private $env;
    /**
     * @var ConfigRepository
     */
    private $configRepository;

    public function __construct(ConfigRepository $configRepository, Env $env)
    {
        $this->configRepository = $configRepository;
        $this->env = $env;
        $this->userConfig = $configRepository->findOneBy([]);
    }

    public function update() {
        $configClass = new \ReflectionClass(Config::class);
        $properties = $configClass->getProperties();
        $transcription = $this->userConfig->getTranscription();

        $updates = 0;

        foreach ($properties as $property) {
            if ($property->getName() !== 'id' && $property->getName() !== 'transcription') {
                $getter = 'get' . ucfirst($property->getName());
                $envName = $transcription[$property->getName()];
                if (
                    $this->userConfig->{$getter}() &&
                    $this->userConfig->{$getter}() !== '' &&
                    $this->userConfig->{$getter}() !== $this->env->get($envName)
                ) {
                    $this->env->set($envName, $this->userConfig->{$getter}());
                    $updates++;
                }
            }
        }

        if ($updates > 0) {
            $this->env->saveFile();
            return true;
        }
        return false;
    }

}
