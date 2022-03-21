<?php

namespace App\Controller;

use App\Configurator\ConfigExtension;
use App\Configurator\ConfVerificator;
use App\Configurator\Env;
use App\Configurator\EnvInjector;
use App\Entity\Config;
use App\Form\ConfigType;
use App\Repository\ConfigRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ConfigController extends AbstractController
{
    /**
     * @Route("/config", name="config")
     * @param Request $request
     * @return Response
     */
    public function add(
        Request $request,
        ConfigRepository $configRepository,
        Env $env,
        EnvInjector $injector
    ): Response {

        $conf = $env->getConfig();

        $configs = $configRepository->findAll();
        $config = new Config();
        if (count($configs) === 1) {
            $config = $configs[0];
        }
        $form = $this->createForm(ConfigType::class, $config);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($config);
            $entityManager->flush();

            if ($injector->update()) {
                $this->addFlash('success', 'Configuration mise à jour');
                return $this->redirectToRoute('config');
            }
            $this->addFlash('error', 'Aucune mise à jour effectuée');
            return $this->redirectToRoute('config');
        }
        if($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Un de vos paramètres est mal rempli');
        }

        return $this->render('config/index.html.twig', [
            'form' => $form->createView(),
            'isConfigComplete' => ConfVerificator::isComplete($conf),
            'isMailConfigOk'   => ConfVerificator::isMailConfigOk($conf),
            'isDefault'        => ConfVerificator::isDefault($conf)
        ]);
    }
}
