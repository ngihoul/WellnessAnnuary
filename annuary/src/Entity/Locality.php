<?php

namespace App\Entity;

use App\Repository\LocalityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\ManyToOne(targetEntity=PostCode::class, inversedBy="localities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $postCode;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="locality")
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }
    public function __toString() {
        return $this->getPostCode()->getPostCode() . ' ' . $this->getName();
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

    public function getPostCode(): ?postCode
    {
        return $this->postCode;
    }

    public function setPostCode(?postCode $postCode): self
    {
        $this->postCode = $postCode;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setLocality($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getLocality() === $this) {
                $user->setLocality(null);
            }
        }

        return $this;
    }
}
