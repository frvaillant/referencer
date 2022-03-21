<?php

namespace App\Entity;

use App\Repository\StudyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\ExistsFilter;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass=StudyRepository::class)
 * @ApiResource(
 *     collectionOperations={"get"={"normalization_context"={"groups"="study:list"}}},
 *     itemOperations={"get"={"normalization_context"={"groups"="study:item"}}},
 *     order={"startDate"="ASC"},
 *     paginationEnabled=false,
 * )
 * @ApiFilter(DateFilter::class, properties={"startDate", "endDate"})
 * @ApiFilter(ExistsFilter::class, properties={"endDate", "city"})
 * @ApiFilter(SearchFilter::class, properties={"id": "exact", "category": "exact", "subCategory": "exact", "project": "exact", "startDate": "exact", "endDate": "exact"})
 * @ApiFilter(OrderFilter::class, properties={"title", "category.name", "subCategory.name", "project.name"})
 */
class Study
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"study:list", "study:item"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"study:list", "study:item"})
     */
    private $title;

    /**
     * @ORM\Column(type="date")
     * @Groups({"study:list", "study:item"})
     */
    private $startDate;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"study:list", "study:item"})
     */
    private $endDate;

    /**
     * @ORM\ManyToOne(targetEntity=StudyCategory::class, inversedBy="studies")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"study:list", "study:item"})
     * @ApiSubresource(maxDepth=1)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="studies")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"study:list", "study:item"})
     */
    private $client;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     * @Groups({"study:list", "study:item"})
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="studies")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"study:list", "study:item"})
     */
    private $project;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo2;

    /**
     * @ORM\Column(type="array", nullable=true)
     * @Groups({"study:list", "study:item"})
     */
    private $keywords = [];

    /**
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="studies")
     * @Groups({"study:list", "study:item"})
     */
    private $city;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"study:list", "study:item"})
     */
    private $hideClient = false;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"study:list", "study:item"})
     */
    private $hideCity;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="projects")
     * @Groups({"study:list", "study:item"})
     */
    private $projectManager;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"study:list", "study:item"})
     */
    private $hideProjectManager;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $additionalDescription;

    /**
     * @ORM\ManyToMany(targetEntity=SubCategory::class, inversedBy="studies")
     * @Groups({"study:list", "study:item"})
     */
    private $subCategories;


    public function __construct()
    {
        $this->subCategories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

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

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $picture): self
    {
        $this->logo = $picture;

        return $this;
    }

    public function getLogo2(): ?string
    {
        return $this->logo2;
    }

    public function setLogo2(?string $picture): self
    {
        $this->logo2 = $picture;

        return $this;
    }

    public function getKeywords(): ?array
    {
        return $this->keywords;
    }

    public function getKeywordsAsString(): ?string
    {
        return $this->getKeywords() ? implode(', ', $this->getKeywords()) : '';
    }

    public function setKeywords(?array $keywords): self
    {
        $this->keywords = $keywords;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getHideClient(): ?bool
    {
        return $this->hideClient;
    }

    public function setHideClient(?bool $hideClient): self
    {

        $this->hideClient = $hideClient ?? true;

        return $this;
    }

    public function getHideCity(): ?bool
    {
        return $this->hideCity;
    }

    public function setHideCity(?bool $hideCity): self
    {
        $this->hideCity = $hideCity;

        return $this;
    }

    public function getProjectManager(): ?Client
    {
        return $this->projectManager;
    }

    public function setProjectManager(?Client $projectManager): self
    {
        $this->projectManager = $projectManager;

        return $this;
    }

    public function getHideProjectManager(): ?bool
    {
        return $this->hideProjectManager;
    }

    public function setHideProjectManager(?bool $hideProjectManager): self
    {
        $this->hideProjectManager = $hideProjectManager;

        return $this;
    }

    public function getAdditionalDescription(): ?string
    {
        return $this->additionalDescription;
    }

    public function setAdditionalDescription(?string $additionalDescription): self
    {
        $this->additionalDescription = $additionalDescription;

        return $this;
    }

    public function getSubCategoriesAsText()
    {
        $names = [];
        foreach ($this->getSubCategories() as $subCategory) {
            $names[] = $subCategory->getName();
        }
        return implode(', ', $names);
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
        }

        return $this;
    }

    public function removeSubCategory(SubCategory $subCategory): self
    {
        $this->subCategories->removeElement($subCategory);

        return $this;
    }

}
