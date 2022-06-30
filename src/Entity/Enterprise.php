<?php

namespace App\Entity;

use App\Repository\EnterpriseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use DateTimeImmutable;
use DateTimeInterface;
use Symfony\Component\HttpFoundation\File\File;


#[ORM\Entity(repositoryClass: EnterpriseRepository::class)]
#[Vich\Uploadable]
class Enterprise
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

    #[Vich\UploadableField(mapping: 'enterprise_image', fileNameProperty: 'photo')]
    #[Assert\File(
        maxSize: '1M',
        mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
    )]
    private ?File $photoFile = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $photo;

    #[ORM\Column(type: 'datetime')]
    private ?DateTimeInterface $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'enterprise', targetEntity: User::class)]
    private Collection $users;

    public function __construct()
    {
        $this->updatedAt = new DateTimeImmutable();
        $this->users = new ArrayCollection();
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

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function setPhotoFile(?File $photoFile = null): void
    {
        $this->photoFile = $photoFile;

        if (null !== $photoFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new DateTimeImmutable();
        }
    }

    public function getPhotoFile(): ?File
    {
        return $this->photoFile;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setEnterprise($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getEnterprise() === $this) {
                $user->setEnterprise(null);
            }
        }

        return $this;
    }
}
