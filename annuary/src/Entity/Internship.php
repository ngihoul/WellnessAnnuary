<?php

namespace App\Entity;

use App\Repository\InternshipRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InternshipRepository::class)
 */
class Internship
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=120)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $additionalInformation;

    /**
     * @ORM\Column(type="date")
     */
    private $startAt;

    /**
     * @ORM\Column(type="date")
     */
    private $endAt;

    /**
     * @ORM\Column(type="date")
     */
    private $displayedFrom;

    /**
     * @ORM\Column(type="date")
     */
    private $displayedUntil;

    /**
     * @ORM\ManyToOne(targetEntity=Provider::class, inversedBy="internships")
     * @ORM\JoinColumn(nullable=false)
     */
    private $provider;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getAdditionalInformation(): ?string
    {
        return $this->additionalInformation;
    }

    public function setAdditionalInformation(?string $additionalInformation): self
    {
        $this->additionalInformation = $additionalInformation;

        return $this;
    }

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeInterface $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTimeInterface $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getDisplayedFrom(): ?\DateTimeInterface
    {
        return $this->displayedFrom;
    }

    public function setDisplayedFrom(\DateTimeInterface $displayedFrom): self
    {
        $this->displayedFrom = $displayedFrom;

        return $this;
    }

    public function getDisplayedUntil(): ?\DateTimeInterface
    {
        return $this->displayedUntil;
    }

    public function setDisplayedUntil(\DateTimeInterface $displayedUntil): self
    {
        $this->displayedUntil = $displayedUntil;

        return $this;
    }

    public function getProvider(): ?Provider
    {
        return $this->provider;
    }

    public function setProvider(?Provider $provider): self
    {
        $this->provider = $provider;

        return $this;
    }
}
