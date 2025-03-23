<?php

namespace App\Entity;

use App\Repository\DevisRepository;
use App\Entity\soc\Societe;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: DevisRepository::class)]
class Devis
{
    use Traits\TimestampableTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le champ réference du devis doit etre renseigné")]
    private ?string $ref_devis = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le champ date devis est obligatoire.")]
    #[Assert\Type("\DateTime", message: "Le champ date_devis doit être sous format de date valide.")]
    private ?\DateTime $date_devis = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le champ date_echeance est obligatoire.")]
    #[Assert\Type("\DateTime", message: "Le champ date_ cheance doit être sous format de date valide.")]
    #[Assert\GreaterThan(propertyPath: "date_devis", message: "La date d'échéance doit être postérieure à la date du devis.")]
    private ?\DateTime $date_echeance = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le champ titre du devis doit etre renseigné")]
    private ?string $titre_devis = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(
        max: 1000,
        maxMessage: "Le contenu ne peut pas dépasser 1000 caractères."
    )]
    private ?string $content = null;

    #[ORM\Column]
    private ?float $total_ht = null;

    #[ORM\Column]
    private ?float $tva = null;

    #[ORM\Column]
    private ?float $total_ttc = null;

    #[ORM\Column]
    private ?float $total_remise = null;

    #[ORM\Column(unique: true)]
    #[Gedmo\Slug(fields: ['ref_devis','titre_devis'])]
    private ?string $slug = null;

    #[ORM\ManyToOne(inversedBy: 'devis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Societe $society = null;


    #[ORM\ManyToOne(inversedBy: 'devis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    #[ORM\OneToMany(mappedBy: 'devis', targetEntity: DevisProduit::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $devisProduits;

    public function __construct()
    {
        $this->devisProduits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRefDevis(): ?string
    {
        return $this->ref_devis;
    }

    public function setRefDevis(string $ref_devis): static
    {
        $this->ref_devis = $ref_devis;

        return $this;
    }

    public function getDateDevis(): ?\DateTime
    {
        return $this->date_devis;
    }

    public function setDateDevis(\DateTime $date_devis): static
    {
        $this->date_devis = $date_devis;

        return $this;
    }

    public function getDateEcheance(): ?\DateTime
    {
        return $this->date_echeance;
    }

    public function setDateEcheance(\DateTime $date_echeance): static
    {
        $this->date_echeance = $date_echeance;

        return $this;
    }

    public function getTitreDevis(): ?string
    {
        return $this->titre_devis;
    }

    public function setTitreDevis(string $titre_devis): static
    {
        $this->titre_devis = $titre_devis;

        return $this;
    }

    public function getTotalHt(): ?float
    {
        return $this->total_ht;
    }

    public function setTotalHt(float $total_ht): static
    {
        $this->total_ht = $total_ht;

        return $this;
    }

    public function getTva(): ?float
    {
        return $this->tva;
    }

    public function setTva(float $tva): static
    {
        $this->tva = $tva;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getTotalTtc(): ?float
    {
        return $this->total_ttc;
    }

    public function setTotalTtc(float $total_ttc): static
    {
        $this->total_ttc = $total_ttc;

        return $this;
    }

    public function getTotalRemise(): ?float
    {
        return $this->total_remise;
    }

    public function setTotalRemise(float $total_remise): static
    {
        $this->total_remise = $total_remise;

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

    public function getSociety(): ?Societe
    {
        return $this->society;
    }

    public function setSociety(?Societe $society): static
    {
        $this->society = $society;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Collection<int, DevisProduit>
     */
    public function getDevisProduits(): Collection
    {
        return $this->devisProduits;
    }

    public function addDevisProduit(DevisProduit $devisProduit): static
    {
        if (!$this->devisProduits->contains($devisProduit)) {
            $this->devisProduits->add($devisProduit);
            $devisProduit->setDevis($this);
        }

        return $this;
    }

    public function removeDevisProduit(DevisProduit $devisProduit): static
    {
        if ($this->devisProduits->removeElement($devisProduit)) {
            // set the owning side to null (unless already changed)
            if ($devisProduit->getDevis() === $this) {
                $devisProduit->setDevis(null);
            }
        }

        return $this;
    }
}
