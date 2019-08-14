<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\TransactionRepository")
 */
class Transaction
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $code;

    /**
     * @ORM\Column(type="bigint")
     */
    private $montant;

    /**
     * @ORM\Column(type="bigint")
     */
    private $frais;

    /**
     * @ORM\Column(type="bigint")
     */
    private $total;

    /**
     * @ORM\Column(type="bigint")
     */
    private $commissionWari;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $CommissionPartenaire;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $commissionEtat;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur", inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $guichetier;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Expediteur", inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Expediteur;

   

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateEnvoie;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateRetrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeOp;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Beneficiaire", inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $beneficiaire;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getFrais(): ?int
    {
        return $this->frais;
    }

    public function setFrais(int $frais): self
    {
        $this->frais = $frais;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getCommissionWari(): ?int
    {
        return $this->commissionWari;
    }

    public function setCommissionWari(int $commissionWari): self
    {
        $this->commissionWari = $commissionWari;

        return $this;
    }

    public function getCommissionPartenaire(): ?int
    {
        return $this->CommissionPartenaire;
    }

    public function setCommissionPartenaire(?int $CommissionPartenaire): self
    {
        $this->CommissionPartenaire = $CommissionPartenaire;

        return $this;
    }

    public function getCommissionEtat(): ?int
    {
        return $this->commissionEtat;
    }

    public function setCommissionEtat(?int $commissionEtat): self
    {
        $this->commissionEtat = $commissionEtat;

        return $this;
    }

    public function getGuichetier(): ?Utilisateur
    {
        return $this->guichetier;
    }

    public function setGuichetier(?Utilisateur $guichetier): self
    {
        $this->guichetier = $guichetier;

        return $this;
    }

    public function getExpediteur(): ?Expediteur
    {
        return $this->Expediteur;
    }

    public function setExpediteur(?Expediteur $Expediteur): self
    {
        $this->Expediteur = $Expediteur;

        return $this;
    }


    public function getDateEnvoie(): ?\DateTimeInterface
    {
        return $this->dateEnvoie;
    }

    public function setDateEnvoie(?\DateTimeInterface $dateEnvoie): self
    {
        $this->dateEnvoie = $dateEnvoie;

        return $this;
    }

    public function getDateRetrait(): ?\DateTimeInterface
    {
        return $this->dateRetrait;
    }

    public function setDateRetrait(?\DateTimeInterface $dateRetrait): self
    {
        $this->dateRetrait = $dateRetrait;

        return $this;
    }

    public function getTypeOp(): ?string
    {
        return $this->typeOp;
    }

    public function setTypeOp(string $typeOp): self
    {
        $this->typeOp = $typeOp;

        return $this;
    }

    public function getBeneficiaire(): ?Beneficiaire
    {
        return $this->beneficiaire;
    }

    public function setBeneficiaire(?Beneficiaire $beneficiaire): self
    {
        $this->beneficiaire = $beneficiaire;

        return $this;
    }
}
