<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateEnvoie;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateRetrait;

  
   

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur", inversedBy="transactionRetrait")
     */
    private $guichetierRetrait;

    /**
     * @ORM\Column(type="integer")
     */
    private $commissionRetrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="integer")
     */
    private $telephone;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $numeroPiece;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $typePiece;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etat;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenomb;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomb;

    /**
     * @ORM\Column(type="integer")
     */
    private $telephoneb;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $numeroPieceb;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $typePieceb;

  

   

  

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


 

    public function getGuichetierRetrait(): ?Utilisateur
    {
        return $this->guichetierRetrait;
    }

    public function setGuichetierRetrait(?Utilisateur $guichetierRetrait): self
    {
        $this->guichetierRetrait = $guichetierRetrait;

        return $this;
    }

    public function getCommissionRetrait(): ?int
    {
        return $this->commissionRetrait;
    }

    public function setCommissionRetrait(int $commissionRetrait): self
    {
        $this->commissionRetrait = $commissionRetrait;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(int $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getNumeroPiece(): ?int
    {
        return $this->numeroPiece;
    }

    public function setNumeroPiece(?int $numeroPiece): self
    {
        $this->numeroPiece = $numeroPiece;

        return $this;
    }

    public function getTypePiece(): ?string
    {
        return $this->typePiece;
    }

    public function setTypePiece(?string $typePiece): self
    {
        $this->typePiece = $typePiece;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getPrenomb(): ?string
    {
        return $this->prenomb;
    }

    public function setPrenomb(string $prenomb): self
    {
        $this->prenomb = $prenomb;

        return $this;
    }

    public function getNomb(): ?string
    {
        return $this->nomb;
    }

    public function setNomb(string $nomb): self
    {
        $this->nomb = $nomb;

        return $this;
    }

    public function getTelephoneb(): ?int
    {
        return $this->telephoneb;
    }

    public function setTelephoneb(int $telephoneb): self
    {
        $this->telephoneb = $telephoneb;

        return $this;
    }

    public function getNumeroPieceb(): ?int
    {
        return $this->numeroPieceb;
    }

    public function setNumeroPieceb(?int $numeroPieceb): self
    {
        $this->numeroPieceb = $numeroPieceb;

        return $this;
    }

    public function getTypePieceb(): ?string
    {
        return $this->typePieceb;
    }

    public function setTypePieceb(?string $typePieceb): self
    {
        $this->typePieceb = $typePieceb;

        return $this;
    }

    

   

    /**
     * Get the value of guichetier
     */ 
    public function getGuichetier()
    {
        return $this->guichetier;
    }

    /**
     * Set the value of guichetier
     *
     * @return  self
     */ 
    public function setGuichetier($guichetier)
    {
        $this->guichetier = $guichetier;

        return $this;
    }
}