<?php

namespace App\Entity;

use App\Repository\PostCodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostCodeRepository::class)
 */
class PostCode
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private $postCode;

    /**
     * @ORM\OneToMany(targetEntity=Locality::class, mappedBy="postCode")
     */
    private $localities;

    /**
     * @ORM\ManyToOne(targetEntity=Municipality::class, inversedBy="postCodes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $municipality;

    public function __construct()
    {
        $this->localities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPostCode(): ?string
    {
        return $this->postCode;
    }

    public function setPostCode(string $postCode): self
    {
        $this->postCode = $postCode;

        return $this;
    }

    /**
     * @return Collection|Locality[]
     */
    public function getLocalities(): Collection
    {
        return $this->localities;
    }

    public function addLocality(Locality $locality): self
    {
        if (!$this->localities->contains($locality)) {
            $this->localities[] = $locality;
            $locality->setPostCode($this);
        }

        return $this;
    }

    public function removeLocality(Locality $locality): self
    {
        if ($this->localities->removeElement($locality)) {
            // set the owning side to null (unless already changed)
            if ($locality->getPostCode() === $this) {
                $locality->setPostCode(null);
            }
        }

        return $this;
    }

    public function getMunicipality(): ?Municipality
    {
        return $this->municipality;
    }

    public function setMunicipality(?Municipality $municipality): self
    {
        $this->municipality = $municipality;

        return $this;
    }
}
