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
    // No website required to register on the annuary
    private $website;

    /**
     * @ORM\Column(type="string", length=25)
     */
    // PhoneNumber length limited to 25 chars to anticipate internalization
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=15)
     */
    // VTA Number length limited to 15 chars to anticipate internalization
    private $VTANumber;

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

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getVTANumber(): ?string
    {
        return $this->VTANumber;
    }

    public function setVTANumber(string $VTANumber): self
    {
        $this->VTANumber = $VTANumber;

        return $this;
    }
}
