<?php


namespace App\Controller\Email;


use App\Configurator\ConfVerificator;
use App\Configurator\Env;
use App\Form\MailType;
use App\Form\ProjectType;
use App\Service\Email\EmailMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class EmailController extends AbstractController
{
    const FILE_LIMIT_SIZE = 2000000;

        /**
         * @Route("/email/send", name="send_email")
         */
        public function index(MessageBusInterface $bus, Request $request, KernelInterface $kernel, Env $env)
        {
            $form = $this->createForm(MailType::class);
            $form->handleRequest($request);

            if (!is_file($kernel->getProjectDir() . '/public/pdf/references.pdf')) {
                $this->addFlash('error', 'Vous n\'avez généré aucun dossier de référence. Générez un dossier avant de pouvoir l\'envoyer');
                return $this->redirectToRoute('references');
            }
            $file = new File($kernel->getProjectDir() . '/public/pdf/references.pdf');

            if($file->isFile() && $file->getSize() > self::FILE_LIMIT_SIZE) {
                $this->addFlash('error', 'Votre dossier est trop gros pour être envoyé par mail. Téléchargez-le et utilisez un service de transfert de fichiers.');
                return $this->redirectToRoute('home');
            }

            if ($form->isSubmitted() && $form->isValid()) {

                    $bus->dispatch(new EmailMessage($form->getData()));
                    $this->addFlash('success', 'Le message a bien été envoyé');
                    return $this->redirectToRoute('home_base');

            }

            return $this->render('email/send_email.html.twig', [
                'form' => $form->createView()
            ]);
        }

}
