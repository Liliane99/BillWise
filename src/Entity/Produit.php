<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use App\Entity\soc\Societe;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Produit
{
    use Traits\TimestampableTrait;
    public const CATEGORIES = [
        'distanciel' => 'distanciel',
        'présentiel' => 'présentiel',
        'hybride' => 'hybride',
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(
        message: 'Il faut renseigner la désignation du produit.'
    )]
    private ?string $designation = null;

    #[ORM\Column(nullable: true)]
    private ?int $nb_apprenant_min = null;

    #[ORM\Column(nullable: true)]
    private ?int $nb_apprenant_max = null;

    #[ORM\Column]
    #[Assert\NotBlank(
        message: 'Le prix unitaire ne doit pas être vide.'
    )]
    private ?float $price_unit = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message: 'La catégorie ne doit pas être vide.'
    )]
    private ?string $categorie = null;

    #[ORM\Column]
    #[Assert\Type(
        type: 'float',
        message: 'La valeur de la tva doit etre sous format 0.2 par exemple pour 20%.'
    )]
    private ?float $taux_tva = null;


    #[ORM\Column(nullable: true)]
    private ?int $duration = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $exigeance = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $certification = null;

    #[ORM\Column(unique: true)]
    #[Gedmo\Slug(fields: ['designation','id'])]
    private ?string $slug = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Societe $society = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: DevisProduit::class, orphanRemoval: true)]
    private Collection $devisProduits;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: FactureProduit::class, orphanRemoval: true)]
    private Collection $factureProduits;

    public function __construct()
    {
        $this->devisProduits = new ArrayCollection();
        $this->factureProduits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): static
    {
        $this->designation = $designation;

        return $this;
    }

    public function getNbApprenantMin(): ?int
    {
        return $this->nb_apprenant_min;
    }

    public function setNbApprenantMin(?int $nb_apprenant_min): static
    {
        $this->nb_apprenant_min = $nb_apprenant_min;

        return $this;
    }

    public function getNbApprenantMax(): ?int
    {
        return $this->nb_apprenant_max;
    }

    public function setNbApprenantMax(?int $nb_apprenant_max): static
    {
        $this->nb_apprenant_max = $nb_apprenant_max;

        return $this;
    }

    public function getPriceUnit(): ?float
    {
        return $this->price_unit;
    }

    public function setPriceUnit(float $price_unit): static
    {
        $this->price_unit = $price_unit;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getTauxTva(): ?float
    {
        return $this->taux_tva;
    }

    public function setTauxTva(float $taux_tva): static
    {
        $this->taux_tva = $taux_tva;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
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

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getExigeance(): ?string
    {
        return $this->exigeance;
    }

    public function setExigeance(?string $exigeance): static
    {
        $this->exigeance = $exigeance;

        return $this;
    }

    public function getCertification(): ?string
    {
        return $this->certification;
    }

    public function setCertification(?string $certification): static
    {
        $this->certification = $certification;

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
            $devisProduit->setProduct($this);
        }

        return $this;
    }

    public function removeDevisProduit(DevisProduit $devisProduit): static
    {
        if ($this->devisProduits->removeElement($devisProduit)) {
            // set the owning side to null (unless already changed)
            if ($devisProduit->getProduct() === $this) {
                $devisProduit->setProduct(null);
            }
        }

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
            $factureProduit->setProduct($this);
        }

        return $this;
    }

    public function removeFactureProduit(FactureProduit $factureProduit): static
    {
        if ($this->factureProduits->removeElement($factureProduit)) {
            // set the owning side to null (unless already changed)
            if ($factureProduit->getProduct() === $this) {
                $factureProduit->setProduct(null);
            }
        }

        return $this;
    }
}
