<?php

namespace App\Entity;

use App\Repository\StudyCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=StudyCategoryRepository::class)
 * @ApiResource(
 *     collectionOperations={"get"={"normalization_context"={"groups"="studyCategory:list"}}},
 *     itemOperations={"get"={"normalization_context"={"groups"="studyCategory:item"}}},
 *     order={"name"="ASC"},
 *     paginationEnabled=false
 * )
 */
class StudyCategory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"studyCategory:list", "studyCategory:item", "study:list", "study:item", "subCategory:list", "subCategory:item"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"studyCategory:list", "studyCategory:item", "study:list", "study:item", "subCategory:list", "subCategory:item"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Study::class, mappedBy="category")
     */
    private $studies;

    /**
     * @ORM\OneToMany(targetEntity=SubCategory::class, mappedBy="category")
     * @Groups({"studyCategory:list", "studyCategory:item"})
     */
    private $subCategories;

    public function __construct()
    {
        $this->studies = new ArrayCollection();
        $this->subCategories = new ArrayCollection();
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
            $study->setCategory($this);
        }

        return $this;
    }

    public function removeStudy(Study $study): self
    {
        if ($this->studies->contains($study)) {
            $this->studies->removeElement($study);
            // set the owning side to null (unless already changed)
            if ($study->getCategory() === $this) {
                $study->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SubCategory[]
     */
    public function getSubCategories(): Collection
    {
        return $this->subCategories;
    }

    public function addSubCategory(SubCategory $subCategory): self
    {
        if (!$this->subCategories->contains($subCategory)) {
            $this->subCategories[] = $subCategory;
            $subCategory->setCategory($this);
        }

        return $this;
    }

    public function removeSubCategory(SubCategory $subCategory): self
    {
        if ($this->subCategories->contains($subCategory)) {
            $this->subCategories->removeElement($subCategory);
            // set the owning side to null (unless already changed)
            if ($subCategory->getCategory() === $this) {
                $subCategory->setCategory(null);
            }
        }

        return $this;
    }

}
