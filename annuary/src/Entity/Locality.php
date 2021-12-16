<?php

namespace App\Entity;

use App\Repository\LocalityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LocalityRepository::class)
 */
class Locality
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
     * @ORM\ManyToOne(targetEntity=postCode::class, inversedBy="localities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $postCode;

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

    public function getPostCode(): ?postCode
    {
        return $this->postCode;
    }

    public function setPostCode(?postCode $postCode): self
    {
        $this->postCode = $postCode;

        return $this;
    }
}
