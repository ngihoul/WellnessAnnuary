<?php

namespace App\Entity;

use App\Repository\ProviderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProviderRepository::class)
 */
class Provider
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $website;

    // Length of phoneNumber limited to 25 to anticipate internationalization
    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $phoneNumber;

    // Length of VATNumber limited to 15 to anticipate internalization
    // VAT Number required to register on the website
    /**
     * @ORM\Column(type="string", length=15)
     */
    private $VATNumber;

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

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getVATNumber(): ?string
    {
        return $this->VATNumber;
    }

    public function setVATNumber(string $VATNumber): self
    {
        $this->VATNumber = $VATNumber;

        return $this;
    }
}
