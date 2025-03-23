<?php

namespace App\Entity;

use App\Repository\DevisProduitRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\soc\Societe;

#[ORM\Entity(repositoryClass: DevisProduitRepository::class)]
class DevisProduit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'devisProduits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Devis $devis = null;

    #[ORM\ManyToOne(inversedBy: 'devisProduits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produit $product = null;

    #[ORM\Column]
    private ?int $nb_apprenant = null;

    #[ORM\Column]
    private ?float $montant_ht = null;

    #[ORM\Column]
    private ?float $taxe_tva = null;

    #[ORM\Column]
    private ?float $montant_remise = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDevis(): ?Devis
    {
        return $this->devis;
    }

    public function setDevis(?Devis $devis): static
    {
        $this->devis = $devis;

        return $this;
    }

    public function getProduct(): ?Produit
    {
        return $this->product;
    }

    public function setProduct(?Produit $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getNbApprenant(): ?int
    {
        return $this->nb_apprenant;
    }

    public function setNbApprenant(int $nb_apprenant): static
    {
        $this->nb_apprenant = $nb_apprenant;

        return $this;
    }

    public function getMontantHt(): ?float
    {
        return $this->montant_ht;
    }

    public function setMontantHt(float $montant_ht): static
    {
        $this->montant_ht = $montant_ht;

        return $this;
    }

    public function getTaxeTva(): ?float
    {
        return $this->taxe_tva;
    }

    public function setTaxeTva(float $taxe_tva): static
    {
        $this->taxe_tva = $taxe_tva;

        return $this;
    }

    public function getMontantRemise(): ?float
    {
        return $this->montant_remise;
    }

    public function setMontantRemise(float $montant_remise): static
    {
        $this->montant_remise = $montant_remise;

        return $this;
    }
}
