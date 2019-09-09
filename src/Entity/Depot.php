<?php

namespace App\Entity;

use App\Entity\Compte;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;




/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\DepotRepository")
 */
class Depot
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"liste-depot","liste-compte"})
     */
    private $id;

    /**
     * @ORM\Column(type="bigint")
     * @Assert\NotBlank(message="Veuillez resnseigner ce champ")   
     * @Assert\Positive
     * @Assert\GreaterThan(75000, message="Un salaire doit etre superieur  ou égal à 75000")
     * @Groups({"liste-depot","liste-compte","liste-code"})
     */
    private $montant;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"liste-depot","liste-compte","liste-code"})
     */
    private $dateDepot;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Compte", inversedBy="depots")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"liste-depot","liste-compte"})
     */
    private $compte;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur", inversedBy="depots")
     * @Groups({"liste-depot","liste-compte"})
     */
    private $utilisateur;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateDepot(): ?\DateTimeInterface
    {
        return $this->dateDepot;
    }

    public function setDateDepot(\DateTimeInterface $dateDepot): self
    {
        $this->dateDepot = $dateDepot;

        return $this;
    }

    public function getCompte(): ?compte
    {
        return $this->compte;
    }

    public function setCompte(?compte $compte): self
    {
        $this->compte = $compte;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }
}
