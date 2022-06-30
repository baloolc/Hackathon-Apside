<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[Assert\NotBlank()]
    #[Assert\Length(
        max: 255
    )]
    #[ORM\Column(type: 'string', length: 255)]
    private string $name;


    #[Assert\Length(
        max: 255
    )]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $society;

    #[Assert\NotBlank()]
    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'projects')]
    private Collection $user;

    #[ORM\ManyToMany(targetEntity: Enterprise::class, inversedBy: 'projects')]
    private Collection $enterprise;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->enterprise = new ArrayCollection();
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

    public function getSociety(): ?string
    {
        return $this->society;
    }

    public function setSociety(?string $society): self
    {
        $this->society = $society;

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

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->user->removeElement($user);

        return $this;
    }

    /**
     * @return Collection<int, Enterprise>
     */
    public function getEnterprise(): Collection
    {
        return $this->enterprise;
    }

    public function addEnterprise(Enterprise $enterprise): self
    {
        if (!$this->enterprise->contains($enterprise)) {
            $this->enterprise[] = $enterprise;
        }

        return $this;
    }

    public function removeEnterprise(Enterprise $enterprise): self
    {
        $this->enterprise->removeElement($enterprise);

        return $this;
    }
}
