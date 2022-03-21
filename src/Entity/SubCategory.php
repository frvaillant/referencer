<?php

namespace App\Entity;

use App\Repository\SubCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Annotation\ApiFilter;

/**
 * @ORM\Entity(repositoryClass=SubCategoryRepository::class)
 * @ApiResource(
 *     collectionOperations={"get"={"normalization_context"={"groups"="subCategory:list"}}},
 *     itemOperations={"get"={"normalization_context"={"groups"="subCategory:item"}}},
 *     order={"name"="ASC"},
 *     paginationEnabled=false
 * )
 * @ApiFilter(SearchFilter::class, properties={"category": "exact"})
 */
class SubCategory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"subCategory:list", "subCategory:item", "study:list", "study:item", "studyCategory:item", "studyCategory:list", "studyCategory:item"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"subCategory:list", "subCategory:item", "study:list", "study:item", "studyCategory:item", "studyCategory:list", "studyCategory:item"})
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=StudyCategory::class, inversedBy="subCategories")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"subCategory:list", "subCategory:item"})
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=Study::class, mappedBy="subCategories")
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

    public function getCategory(): ?StudyCategory
    {
        return $this->category;
    }

    public function setCategory(?StudyCategory $category): self
    {
        $this->category = $category;

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
            $study->addSubCategory($this);
        }

        return $this;
    }

    public function removeStudy(Study $study): self
    {
        if ($this->studies->removeElement($study)) {
            $study->removeSubCategory($this);
        }

        return $this;
    }

}
