<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 * @ApiResource(
 *     collectionOperations={"get"={"normalization_context"={"groups"="project:list"}}},
 *     itemOperations={"get"={"normalization_context"={"groups"="project:item"}}},
 *     order={"name"="ASC"},
 *     paginationEnabled=false
 * )
 */
class Project
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"project:list", "project:item", "study:list", "study:item"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"project:list", "project:item", "study:list", "study:item"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Study::class, mappedBy="project")
     */
    private $studies;

    public function __construct()
    {
        $this->studies = new ArrayCollection();
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
            $study->setProject($this);
        }

        return $this;
    }

    public function removeStudy(Study $study): self
    {
        if ($this->studies->contains($study)) {
            $this->studies->removeElement($study);
            // set the owning side to null (unless already changed)
            if ($study->getProject() === $this) {
                $study->setProject(null);
            }
        }

        return $this;
    }
}
