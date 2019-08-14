<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\BeneficiaireRepository")
 */
class Beneficiaire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenomB;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomB;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresseB;

    /**
     * @ORM\Column(type="integer")
     */
    private $telephoneB;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numeroPieceB;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $typePieceB;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction", mappedBy="beneficiaire")
     */
    private $transactions;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrenomB(): ?string
    {
        return $this->prenomB;
    }

    public function setPrenomB(string $prenomB): self
    {
        $this->prenomB = $prenomB;

        return $this;
    }

    public function getNomB(): ?string
    {
        return $this->nomB;
    }

    public function setNomB(string $nomB): self
    {
        $this->nomB = $nomB;

        return $this;
    }

    public function getAdresseB(): ?string
    {
        return $this->adresseB;
    }

    public function setAdresseB(?string $adresseB): self
    {
        $this->adresseB = $adresseB;

        return $this;
    }

    public function getTelephoneB(): ?int
    {
        return $this->telephoneB;
    }

    public function setTelephoneB(int $telephoneB): self
    {
        $this->telephoneB = $telephoneB;

        return $this;
    }

    public function getNumeroPieceB(): ?int
    {
        return $this->numeroPieceB;
    }

    public function setNumeroPieceB(?int $numeroPieceB): self
    {
        $this->numeroPieceB = $numeroPieceB;

        return $this;
    }

    public function getTypePieceB(): ?string
    {
        return $this->typePieceB;
    }

    public function setTypePieceB(?string $typePieceB): self
    {
        $this->typePieceB = $typePieceB;

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setBeneficiaire($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->contains($transaction)) {
            $this->transactions->removeElement($transaction);
            // set the owning side to null (unless already changed)
            if ($transaction->getBeneficiaire() === $this) {
                $transaction->setBeneficiaire(null);
            }
        }

        return $this;
    }
}
