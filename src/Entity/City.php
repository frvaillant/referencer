<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CityRepository::class)
 * @ApiResource(
 *     collectionOperations={"get"={"normalization_context"={"groups"="city:list"}}},
 *     itemOperations={"get"={"normalization_context"={"groups"="city:item"}}},
 *     order={"cityName"="ASC"},
 *     paginationEnabled=true,
 * )
 */
class City
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"study:list", "study:item", "city:list", "city:item"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=6)
     * @Groups({"city:item"})
     */
    private $codeInsee;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"study:list", "study:item", "city:list", "city:item"})
     */
    private $cityName;

    /**
     * @ORM\Column(type="string", length=5)
     * @Groups({"study:list", "study:item", "city:item"})
     */
    private $zipCode;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"study:list", "study:item", "city:item"})
     */
    private $latitude;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"study:list", "study:item", "city:item"})
     */
    private $longitude;

    /**
     * @ORM\OneToMany(targetEntity=Study::class, mappedBy="city")
     */
    private $studies;

    public function __construct()
    {
        $this->studies = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getCityName();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodeInsee()
    {
        return $this->codeInsee;
    }

    /**
     * @param mixed $codeInsee
     */
    public function setCodeInsee($codeInsee): self
    {
        $this->codeInsee = $codeInsee;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCityName()
    {
        return $this->cityName;
    }

    /**
     * @param mixed $cityName
     */
    public function setCityName($cityName): self
    {
        $this->cityName = $cityName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @param mixed $zipCode
     */
    public function setZipCode($zipCode): self
    {
        $this->zipCode = $zipCode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude): self
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude): self
    {
        $this->longitude = $longitude;
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
            $study->setCity($this);
        }

        return $this;
    }

    public function removeStudy(Study $study): self
    {
        if ($this->studies->contains($study)) {
            $this->studies->removeElement($study);
            // set the owning side to null (unless already changed)
            if ($study->getCity() === $this) {
                $study->setCity(null);
            }
        }

        return $this;
    }



}
