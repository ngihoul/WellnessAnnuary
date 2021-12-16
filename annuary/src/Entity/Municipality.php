<?php

namespace App\Entity;

use App\Repository\MunicipalityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MunicipalityRepository::class)
 */
class Municipality
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=PostCode::class, mappedBy="municipality")
     */
    private $postCodes;

    public function __construct()
    {
        $this->postCodes = new ArrayCollection();
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
     * @return Collection|PostCode[]
     */
    public function getPostCodes(): Collection
    {
        return $this->postCodes;
    }

    public function addPostCode(PostCode $postCode): self
    {
        if (!$this->postCodes->contains($postCode)) {
            $this->postCodes[] = $postCode;
            $postCode->setMunicipality($this);
        }

        return $this;
    }

    public function removePostCode(PostCode $postCode): self
    {
        if ($this->postCodes->removeElement($postCode)) {
            // set the owning side to null (unless already changed)
            if ($postCode->getMunicipality() === $this) {
                $postCode->setMunicipality(null);
            }
        }

        return $this;
    }
}
