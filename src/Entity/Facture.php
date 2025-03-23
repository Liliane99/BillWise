<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use App\Entity\soc\Societe;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity(repositoryClass=FactureRepository::class)
 */

#[ORM\Entity(repositoryClass: FactureRepository::class)]
class Facture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
     private ?string $ref_facture = null;

    #[ORM\Column (nullable: true)]
    private ?\DateTime $date_facture = null;

    #[ORM\Column(length: 255)]
    private ?string $titre_facture = null;

    #[ORM\Column]
    private ?float $total_ht = null;

    #[ORM\Column]
    private ?float $tva = null;

    #[ORM\Column]
    private ?float $total_ttc = null;

    #[ORM\Column (nullable: true)]
    private ?float $total_remise = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $condition_termes = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $condition = null;

    #[ORM\Column (nullable: true)]
    private ?\DateTime $date_echeance = null;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Societe $society = null;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Client $client = null;

   

    #[ORM\ManyToOne(inversedBy: 'factures')]
    #[ORM\JoinColumn(nullable: true)]
    
    /**
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(name="creator_id", referencedColumnName="id")
     */
    private ?User $creator = null;

    #[ORM\Column(nullable: true)]

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]

     /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\OneToMany(mappedBy: 'facture', targetEntity: FactureProduit::class, orphanRemoval: true)]
    private Collection $factureProduits;

    #[ORM\OneToMany(mappedBy: 'facture', targetEntity: Paiement::class, orphanRemoval: true)]
    private Collection $paiements;

    public function __construct()
    {
        $this->factureProduits = new ArrayCollection();
        $this->paiements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRefFacture(): ?string
    {
        return $this->ref_facture;
    }

    public function setRefFacture(string $ref_facture): static
    {
        $this->ref_facture = $ref_facture;

        return $this;
    }

    public function getDateFacture(): ?\DateTime
    {
        return $this->date_facture;
    }

    public function setDateFacture(\DateTime $date_facture): static
    {
        $this->date_facture = $date_facture;

        return $this;
    }

    public function getTitreFacture(): ?string
    {
        return $this->titre_facture;
    }

    public function setTitreFacture(string $titre_facture): static
    {
        $this->titre_facture = $titre_facture;

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

    public function getConditionTermes(): ?string
    {
        return $this->condition_termes;
    }

    public function setConditionTermes(?string $condition_termes): static
    {
        $this->condition_termes = $condition_termes;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCondition(): ?string
    {
        return $this->condition;
    }

    public function setCondition(?string $condition): static
    {
        $this->condition = $condition;

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

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): static
    {
        $this->creator = $creator;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection<int, FactureProduit>
     */
    public function getFactureProduits(): Collection
    {
        return $this->factureProduits;
    }

    public function addFactureProduit(FactureProduit $factureProduit): static
    {
        if (!$this->factureProduits->contains($factureProduit)) {
            $this->factureProduits->add($factureProduit);
            $factureProduit->setFacture($this);
        }

        return $this;
    }

    public function removeFactureProduit(FactureProduit $factureProduit): static
    {
        if ($this->factureProduits->removeElement($factureProduit)) {
            // set the owning side to null (unless already changed)
            if ($factureProduit->getFacture() === $this) {
                $factureProduit->setFacture(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Paiement>
     */
    public function getPaiements(): Collection
    {
        return $this->paiements;
    }

    public function addPaiement(Paiement $paiement): static
    {
        if (!$this->paiements->contains($paiement)) {
            $this->paiements->add($paiement);
            $paiement->setFacture($this);
        }

        return $this;
    }

    public function removePaiement(Paiement $paiement): static
    {
        if ($this->paiements->removeElement($paiement)) {
            // set the owning side to null (unless already changed)
            if ($paiement->getFacture() === $this) {
                $paiement->setFacture(null);
            }
        }

        return $this;
    }
}