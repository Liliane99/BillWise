<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Entity\soc\Societe;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[Vich\Uploadable]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'Il existe déjà un compte ayant cet email')]
#[ORM\HasLifecycleCallbacks]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use Traits\TimestampableTrait;

    public const ROLES = [
        'Admin' => 'ROLE_ADMIN',
        'Comptable' => 'ROLE_COMPTABLE',
        'User' => 'ROLE_USER',
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: "L'email est obligatoire.")]
    #[Assert\Email(message: "Le format de l'email est invalide.")]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank(message: "Le mot de passe est obligatoire.")]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $surname = null;


    #[Vich\UploadableField(mapping: 'profilepicUser', fileNameProperty: 'profilePictureName')]
    private ?File $profilePictureFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $profilePictureName = null;


    #[ORM\ManyToMany(targetEntity: societe::class, inversedBy: 'users')]
    private Collection $societe_id;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isVerified = false;
    
    #[ORM\Column(nullable: true)]
    private ?string $confirmationCode = null;

    #[ORM\Column(unique: true)]
    #[Gedmo\Slug(fields: ['name', 'surname', 'email'])]
    private ?string $slug = null;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->clients = new ArrayCollection();
        $this->produits = new ArrayCollection();
        $this->devis = new ArrayCollection();
        $this->factures = new ArrayCollection();
        $this->paiements = new ArrayCollection();
        $this->societe_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
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

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string|null $slug
     * @return self
     */
    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }


    public function setProfilePictureFile(?File $image = null): void
    {
        $this->profilePictureFile = $image;

        if (null !== $image) {
            $this->updatedAt = new \DateTime();
        }
    }

    public function getProfilePictureFile(): ?File
    {
        return $this->profilePictureFile;
    }

    public function setProfilePictureName(?string $imageName): void
    {
        $this->profilePictureName = $imageName;
    }

    public function getProfilePictureName(): ?string
    {
        return $this->profilePictureName;
    }


    /**
     * @return Collection<int, self>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }


    /**
     * @return Collection<int, societe>
     */
    public function getSocieteId(): Collection
    {
        return $this->societe_id;
    }

    public function addSocieteId(societe $societeId): static
    {
        if (!$this->societe_id->contains($societeId)) {
            $this->societe_id->add($societeId);
        }

        return $this;
    }

    public function removeSocieteId(societe $societeId): static
    {
        $this->societe_id->removeElement($societeId);

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }
    public function getConfirmationCode(): ?string
    {
        return $this->confirmationCode;
    }

    public function setConfirmationCode(?string $confirmationCode): void
    {
        $this->confirmationCode= $confirmationCode;
    }

    
}
