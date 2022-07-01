<?php

namespace App\Entity;

use App\Entity\Enterprise;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use DateTimeImmutable;
use DateTimeInterface;
use PHPUnit\TextUI\XmlConfiguration\File;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[Vich\Uploadable]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[Assert\Length(
        max: 255,
    )]
    #[Assert\NotBlank]
    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[Assert\Length(
        max: 255,
    )]
    #[Assert\NotBlank]
    #[ORM\Column(type: 'string', length: 255)]
    private string $firstname;

    #[Assert\NotBlank()]
    #[Assert\Email()]
    #[Assert\Length(
        max: 255,
    )]
    #[ORM\Column(type: 'string', length: 255)]
    private string $email;

    #[Assert\NotBlank()]
    #[Assert\Length(
        max: 255
    )]
    #[ORM\Column(type: 'string', length: 255)]
    private string $password;

    #[ORM\ManyToOne(targetEntity: Enterprise::class, inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private Enterprise $enterprise;

    #[Assert\NotBlank()]
    #[Assert\Length(
        max: 255
    )]
    #[ORM\Column(type: 'string', length: 255)]
    private string $poste;

    #[Assert\Length(
        max: 255
    )]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $tech;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[Vich\UploadableField(mapping: 'user_image', fileNameProperty: 'avatar')]
    #[Assert\File(
        maxSize: '1M',
        mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
    )]
    private ?File $avatarFile = null;

    #[Assert\Length(
        max: 255
    )]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $avatar;

    #[ORM\Column(type: 'datetime')]
    private ?DateTimeInterface $updatedAt = null;

    #[ORM\ManyToMany(targetEntity: Project::class, mappedBy: 'users')]
    private Collection $projects;

    public function __construct()
    {
        $this->updatedAt = new DateTimeImmutable();
        $this->projects = new ArrayCollection();
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

     /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

        /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEnterprise(): ?Enterprise
    {
        return $this->enterprise;
    }

    public function setEnterprise(?Enterprise $enterprise): self
    {
        $this->enterprise = $enterprise;

        return $this;
    }

    public function getPoste(): ?string
    {
        return $this->poste;
    }

    public function setPoste(string $poste): self
    {
        $this->poste = $poste;

        return $this;
    }

    public function getTech(): ?string
    {
        return $this->tech;
    }

    public function setTech(?string $tech): self
    {
        $this->tech = $tech;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function setAvatarFile(?File $avatarFile = null): void
    {
        $this->photoFile = $avatarFile;

        if (null !== $avatarFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new DateTimeImmutable();
        }
    }

    public function getAvatarFile(): ?File
    {
        return $this->avatarFile;
    }

     /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): self
    {
        if (!$this->projects->contains($project)) {
            $this->projects[] = $project;
            $project->addUser($this);
        }

        return $this;
    }
    

    public function removeProject(Project $project): self
    {
        if ($this->projects->removeElement($project)) {
            $project->removeUser($this);
        }

        return $this;
    }
}
