<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 */
class Client
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"study:list", "study:item"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address2;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $zipCode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phoneNumber1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phoneNumber2;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity=Study::class, mappedBy="client")
     */
    private $studies;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hide = false;

    /**
     * @ORM\OneToMany(targetEntity=Study::class, mappedBy="projectManager")
     */
    private $projects;

    public function __construct()
    {
        $this->studies = new ArrayCollection();
        $this->projects = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress1(): ?string
    {
        return $this->address1;
    }

    public function setAddress1(string $address1): self
    {
        $this->address1 = $address1;

        return $this;
    }

    public function getAddress2(): ?string
    {
        return $this->address2;
    }

    public function setAddress2(?string $address2): self
    {
        $this->address2 = $address2;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPhoneNumber1(): ?string
    {
        return $this->phoneNumber1;
    }

    public function setPhoneNumber1(string $phoneNumber1): self
    {
        $this->phoneNumber1 = $phoneNumber1;

        return $this;
    }

    public function getPhoneNumber2(): ?string
    {
        return $this->phoneNumber2;
    }

    public function setPhoneNumber2(?string $phoneNumber2): self
    {
        $this->phoneNumber2 = $phoneNumber2;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|Study[]
     */
    public function getStudies(): Collection
    {
        return $this->studies;
    }

    public function addStudy(Study $study): self
    {
        if (!$this->studies->contains($study)) {
            $this->studies[] = $study;
            $study->setClient($this);
        }

        return $this;
    }

    public function removeStudy(Study $study): self
    {
        if ($this->studies->contains($study)) {
            $this->studies->removeElement($study);
            // set the owning side to null (unless already changed)
            if ($study->getClient() === $this) {
                $study->setClient(null);
            }
        }

        return $this;
    }

    public function getHide(): ?bool
    {
        return $this->hide;
    }

    public function setHide(?bool $hide): self
    {
        $this->hide = $hide ?? false;

        return $this;
    }

    /**
     * @return Collection|Study[]
     */
    public function getProjects(): ?Collection
    {
        return $this->projects;
    }

    public function addProject(Study $project): self
    {
        if (!$this->projects->contains($project)) {
            $this->projects[] = $project;
            $project->setProjectManager($this);
        }

        return $this;
    }

    public function removeProject(Study $project): self
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getProjectManager() === $this) {
                $project->setProjectManager(null);
            }
        }

        return $this;
    }
}
