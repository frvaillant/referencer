<?php


namespace App\Service\Email;


use App\Configurator\Env;
use App\Repository\StructureRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class EmailMessageHandler implements MessageHandlerInterface
{
    private $session;
    /**
     * @var KernelInterface
     */
    private $kernel;
    /**
     * @var MailerInterface
     */
    private $mailer;

    private $structure;
    private $sender;

    public function __construct(string $sender, SessionInterface $session, KernelInterface $kernel, MailerInterface $mailer, StructureRepository $structureRepository)
    {
        $this->session   = $session;
        $this->kernel    = $kernel;
        $this->mailer    = $mailer;
        $this->sender    = $sender;
        $this->structure = $structureRepository->findMyStructure();
    }

    public function __invoke(EmailMessage $emailMessage)
    {
        $emailData = $emailMessage->getData();
        $email = (new TemplatedEmail())
            ->from($this->sender)
            ->to($emailData['sendTo'])
            ->subject($emailData['object'])
            ->htmlTemplate('email/email_template.html.twig')
            ->context([
                'message'   => nl2br($emailData['message']),
                'structure' => $this->structure,
                'logo'      => $this->getLogo()
            ])
            ->attachFromPath($this->kernel->getProjectDir() . '/public/pdf/references.pdf');

        try {
            $this->mailer->send($email);
            $this->session->getFlashBag()->add('success', 'Le message a bien été envoyé');
        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('error', 'Une erreur s\'est produite lors de l\'envoi du message');
        }
    }

    private function getLogo()
    {
        $logoInfos = explode('/', $this->structure->getLogo());
        if (is_file($this->kernel->getProjectDir() . '/public/uploads' . $this->structure->getLogo() )) {
            return '@images/' . $logoInfos[array_key_last($logoInfos)];
        }
        return null;
    }

}
