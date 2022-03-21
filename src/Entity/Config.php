<?php

namespace App\Entity;

use App\Repository\ConfigRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @ORM\Entity(repositoryClass=ConfigRepository::class)
 */
class Config
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Ip
     */
    private $apiAuthorizedIp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Email()
     */
    private $sender;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $login;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $smtp;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $port;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $encrypt;

    public $transcription = [
        'apiAuthorizedIp' => 'API_IP',
        'sender'          => 'MAILER_SENDER',
        'login'           => 'MAILER_LOGIN',
        'password'        => 'MAILER_PASS',
        'smtp'            => 'MAILER_SMTP',
        'port'            => 'MAILER_PORT',
        'encrypt'         => 'MAILER_ENCRYPT',
    ];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApiAuthorizedIp(): ?string
    {
        return $this->apiAuthorizedIp;
    }

    public function setApiAuthorizedIp(?string $apiAuthorizedIp): self
    {
        $this->apiAuthorizedIp = $apiAuthorizedIp;

        return $this;
    }

    public function getSender(): ?string
    {
        return $this->sender;
    }

    public function setSender(?string $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(?string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSmtp(): ?string
    {
        return $this->smtp;
    }

    public function setSmtp(?string $smtp): self
    {
        $this->smtp = $smtp;

        return $this;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function setPort(?int $port): self
    {
        $this->port = $port;

        return $this;
    }

    public function getEncrypt(): ?string
    {
        return $this->encrypt;
    }

    public function setEncrypt(?string $encrypt): self
    {
        $this->encrypt = $encrypt;

        return $this;
    }

    public function getTranscription(): array
    {
        return $this->transcription;
    }
}
