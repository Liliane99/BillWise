<?php

namespace App\Entity\soc;

use App\Repository\SocieteRepository;
use App\Entity\Client;
use App\Entity\User;
use App\Entity\Produit;
use App\Entity\Paiement;
use App\Entity\FactureProduit;
use App\Entity\Facture;
use App\Entity\DevisProduit;
use App\Entity\Devis;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;


#[ORM\Entity(repositoryClass: SocieteRepository::class)]
#[ORM\Table(name: '`societe`')]
#[Vich\Uploadable]
class Societe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La raison sociale est obligatoire.")]
    private ?string $raison_sociale = null;

    #[ORM\Column(unique: true)]
    #[Gedmo\Slug(fields: ['raison_sociale'])]
    private ?string $slug = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "Le numéro de SIRET est obligatoire.")]
    private ?int $num_siret = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Regex(
        pattern: "/^\d{5}$/",
        message: "Le code postal doit contenir 5 chiffres."
    )]
    private ?string $code_postal = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "La ville est obligatoire.")]
    private ?string $ville = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "L'email est obligatoire.")]
    #[Assert\Email(
        message: "L'adresse email n'est pas une adresse email valide."
    )]
    private ?string $email = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Regex(
        pattern: "/^\d{9}$/",
        message: "Le numéro de téléphone doit contenir 9 chiffres."
    )]
    private ?string $num_tel = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Regex(
        pattern: "/^\d{9}$/",
        message: "Le numéro de téléphone fixe doit contenir 9 chiffres."
    )]
    private ?string $num_fix = null;

    #[Vich\UploadableField(mapping: 'avatar_logo_societe', fileNameProperty: 'avatar_logo')]
    private ?File $avatar_logoFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $avatar_logo = null;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTime $createdAt;

    #[Gedmo\Timestampable(on: 'update')]
    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTime $updatedAt;

    #[Gedmo\Blameable(on: 'create')]
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $createdBy = null;

    #[Gedmo\Blameable(on: 'update')]
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $updatedBy = null;


    #[ORM\OneToMany(mappedBy: 'society', targetEntity: Client::class, orphanRemoval: true)]
    private Collection $clients;

    #[ORM\OneToMany(mappedBy: 'society', targetEntity: Produit::class, orphanRemoval: true)]
    private Collection $produits;

    #[ORM\OneToMany(mappedBy: 'society', targetEntity: Devis::class, orphanRemoval: true)]
    private Collection $devis;

    #[ORM\OneToMany(mappedBy: 'society', targetEntity: Facture::class, orphanRemoval: true)]
    private Collection $factures;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'societe_id')]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->clients = new ArrayCollection();
        $this->produits = new ArrayCollection();
        $this->devis = new ArrayCollection();
        $this->factures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRaisonSociale(): ?string
    {
        return $this->raison_sociale;
    }

    public function setRaisonSociale(string $raison_sociale): static
    {
        $this->raison_sociale = $raison_sociale;

        return $this;
    }

    public function getNumSiret(): ?int
    {
        return $this->num_siret;
    }

    public function setNumSiret(int $num_siret): static
    {
        $this->num_siret = $num_siret;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->code_postal;
    }

    public function setCodePostal(?string $code_postal): static
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): static
    {
        $this->ville = $ville;

        return $this;
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

    public function getNumTel(): ?string
    {
        return $this->num_tel;
    }

    public function setNumTel(?string $num_tel): static
    {
        $this->num_tel = $num_tel;

        return $this;
    }

    public function getNumFix(): ?string
    {
        return $this->num_fix;
    }

    public function setNumFix(?string $num_fix): static
    {
        $this->num_fix = $num_fix;

        return $this;
    }

    public function getAvatarLogo(): ?string
    {
        return $this->avatar_logo;
    }

    public function setAvatarLogo(?string $avatar_logo): static
    {
        $this->avatar_logo = $avatar_logo;

        return $this;
    }

    public function setAvatarLogoFile(?File $avatar_logoFile = null): void
    {
        $this->avatar_logoFile = $avatar_logoFile;

        if (null !== $avatar_logoFile) {
            $this->updatedAt = new \DateTime();
        }
    }

    public function getAvatarLogoFile(): ?File
    {
        return $this->avatar_logoFile;
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

    /**
     * @return Collection<int, Client>
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): static
    {
        if (!$this->clients->contains($client)) {
            $this->clients->add($client);
            $client->setSociety($this);
        }

        return $this;
    }

    public function removeClient(Client $client): static
    {
        if ($this->clients->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getSociety() === $this) {
                $client->setSociety(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setSociety($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            if ($produit->getSociety() === $this) {
                $produit->setSociety(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Devis>
     */
    public function getDevis(): Collection
    {
        return $this->devis;
    }

    public function addDevi(Devis $devi): static
    {
        if (!$this->devis->contains($devi)) {
            $this->devis->add($devi);
            $devi->setSociety($this);
        }

        return $this;
    }

    public function removeDevi(Devis $devi): static
    {
        if ($this->devis->removeElement($devi)) {
            if ($devi->getSociety() === $this) {
                $devi->setSociety(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Facture>
     */
    public function getFactures(): Collection
    {
        return $this->factures;
    }

    public function addFacture(Facture $facture): static
    {
        if (!$this->factures->contains($facture)) {
            $this->factures->add($facture);
            $facture->setSociety($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): static
    {
        if ($this->factures->removeElement($facture)) {
            if ($facture->getSociety() === $this) {
                $facture->setSociety(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addSocieteId($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeSocieteId($this);
        }

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }


    public function setCreatedAt($createdAt): self
    {
        if ($createdAt instanceof \DateTimeImmutable) {
            $createdAt = new \DateTime($createdAt->format('Y-m-d H:i:s'));
        }
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }


    public function setUpdatedAt($updatedAt): self
    {
        if ($updatedAt instanceof \DateTimeImmutable) {
            $updatedAt = new \DateTime($updatedAt->format('Y-m-d H:i:s'));
        }
        $this->updatedAt = $updatedAt;
        return $this;
    }


    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    public function getUpdatedBy(): ?User
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?User $updatedBy): self
    {
        $this->updatedBy = $updatedBy;
        return $this;
    }
}
