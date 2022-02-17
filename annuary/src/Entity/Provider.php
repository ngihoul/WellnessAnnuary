<?php

namespace App\Entity;

use App\Repository\ProviderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProviderRepository::class)
 */
class Provider
{
    const LOGO_DIRECTORY = 'logo_directory';
    const LOGO_DEFAULT = 'default.png';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @ORM\OrderBy({"name", "ASC"})
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

    /**
     * @ORM\OneToMany(targetEntity=Internship::class, mappedBy="provider", orphanRemoval=true)
     */
    private $internships;

    /**
     * @ORM\OneToMany(targetEntity=Promotion::class, mappedBy="provider", orphanRemoval=true)
     */
    private $promotions;

    /**
     * @ORM\ManyToMany(targetEntity=ServiceCategory::class, inversedBy="providers")
     * @ORM\JoinTable(name="provider_category")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $serviceCategories;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="provider", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="provider", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="provider", cascade={"persist", "remove"})
     */
    private $images;

    /**
     * @ORM\ManyToMany(targetEntity=Customer::class, inversedBy="favorites")
     * @ORM\JoinTable(name="favorite")
     */
    private $favorite;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $logo;

    public function __construct()
    {
        $this->internships = new ArrayCollection();
        $this->promotions = new ArrayCollection();
        $this->serviceCategories = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->favorite = new ArrayCollection();
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

    /**
     * @return Collection|Internship[]
     */
    public function getInternships(): Collection
    {
        return $this->internships;
    }

    public function addInternship(Internship $internship): self
    {
        if (!$this->internships->contains($internship)) {
            $this->internships[] = $internship;
            $internship->setProvider($this);
        }

        return $this;
    }

    public function removeInternship(Internship $internship): self
    {
        if ($this->internships->removeElement($internship)) {
            // set the owning side to null (unless already changed)
            if ($internship->getProvider() === $this) {
                $internship->setProvider(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Promotion[]
     */
    public function getPromotions(): Collection
    {
        return $this->promotions;
    }

    public function addPromotion(Promotion $promotion): self
    {
        if (!$this->promotions->contains($promotion)) {
            $this->promotions[] = $promotion;
            $promotion->setProvider($this);
        }

        return $this;
    }

    public function removePromotion(Promotion $promotion): self
    {
        if ($this->promotions->removeElement($promotion)) {
            // set the owning side to null (unless already changed)
            if ($promotion->getProvider() === $this) {
                $promotion->setProvider(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ServiceCategory[]
     */
    public function getServiceCategories(): Collection
    {
        return $this->serviceCategories;
    }

    public function addServiceCategory(ServiceCategory $serviceCategory): self
    {
        if (!$this->serviceCategories->contains($serviceCategory)) {
            $this->serviceCategories[] = $serviceCategory;
        }

        return $this;
    }

    public function removeServiceCategory(ServiceCategory $serviceCategory): self
    {
        $this->serviceCategories->removeElement($serviceCategory);

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setProvider($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getProvider() === $this) {
                $comment->setProvider(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setProvider($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getProvider() === $this) {
                $image->setProvider(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Customer[]
     */
    public function getFavorite(): Collection
    {
        return $this->favorite;
    }

    public function addFavorite(Customer $favorite): self
    {
        if (!$this->favorite->contains($favorite)) {
            $this->favorite[] = $favorite;
        }

        return $this;
    }

    public function removeFavorite(Customer $favorite): self
    {
        $this->favorite->removeElement($favorite);

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

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }
}
