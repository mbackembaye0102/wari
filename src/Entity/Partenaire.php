<?php

namespace App\Entity;

use App\Entity\Profil;
use App\Entity\Utilisateur;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\PartenaireRepository")
 * @UniqueEntity(fields={"entreprise"}, message="Ce nom d'entreprise existe déja")
 */
class Partenaire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"liste-compte","comptes"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank(message="Veuillez resnseigner ce champ")   
     * @Groups({"liste-compte","liste-user","liste-comptes","comptes","user"})
     */
    private $entreprise;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez resnseigner ce champ")   
     * @Groups({"liste-compte","liste-user","liste-comptes","comptes"})
     */
    private $raisonSocial;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank(message="Veuillez resnseigner ce champ")   
     * @Groups({"liste-compte","liste-user","liste-comptes","comptes","user"})
     */
    private $ninea;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"liste-compte","comptes"})
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez resnseigner ce champ")
     * @Groups({"liste-compte","comptes"})   
     */
    private $statut;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Utilisateur", mappedBy="partenaire")
     * @Groups({"liste-user","liste-comptes","comptes","user"})

     */
    private $utilisateurs;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Compte", mappedBy="partenaire")
     * @Groups({"liste-depot","liste-compte","liste-comptes", "liste-user","comptes"})
     */
    private $comptes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Compte", mappedBy="partenaire")
     * @Groups({"liste-user","liste-comptes","liste-compte","liste-comptes","comptes"})
     */
    private $comptePartenaire;

    public function __construct()
    {
        $this->utilisateurs = new ArrayCollection();
        $this->comptes = new ArrayCollection();
        $this->comptePartenaire = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntreprise(): ?string
    {
        return $this->entreprise;
    }

    public function setEntreprise(string $entreprise): self
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    public function getRaisonSocial(): ?string
    {
        return $this->raisonSocial;
    }

    public function setRaisonSocial(string $raisonSocial): self
    {
        $this->raisonSocial = $raisonSocial;

        return $this;
    }

    public function getNinea(): ?string
    {
        return $this->ninea;
    }

    public function setNinea(string $ninea): self
    {
        $this->ninea = $ninea;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * @return Collection|Utilisateur[]
     */
    public function getUtilisateurs(): Collection
    {
        return $this->utilisateurs;
    }

    public function addUtilisateur(Utilisateur $utilisateur): self
    {
        if (!$this->utilisateurs->contains($utilisateur)) {
            $this->utilisateurs[] = $utilisateur;
            $utilisateur->setPartenaire($this);
        }

        return $this;
    }

    public function removeUtilisateur(Utilisateur $utilisateur): self
    {
        if ($this->utilisateurs->contains($utilisateur)) {
            $this->utilisateurs->removeElement($utilisateur);
            // set the owning side to null (unless already changed)
            if ($utilisateur->getPartenaire() === $this) {
                $utilisateur->setPartenaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Compte[]
     */
    public function getComptes(): Collection
    {
        return $this->comptes;
    }

    public function addCompte(Compte $compte): self
    {
        if (!$this->comptes->contains($compte)) {
            $this->comptes[] = $compte;
            $compte->setPartenaire($this);
        }

        return $this;
    }

    public function removeCompte(Compte $compte): self
    {
        if ($this->comptes->contains($compte)) {
            $this->comptes->removeElement($compte);
            // set the owning side to null (unless already changed)
            if ($compte->getPartenaire() === $this) {
                $compte->setPartenaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Compte[]
     */
    public function getComptePartenaire(): Collection
    {
        return $this->comptePartenaire;
    }

    public function addComptePartenaire(Compte $comptePartenaire): self
    {
        if (!$this->comptePartenaire->contains($comptePartenaire)) {
            $this->comptePartenaire[] = $comptePartenaire;
            $comptePartenaire->setPartenaire($this);
        }

        return $this;
    }

    public function removeComptePartenaire(Compte $comptePartenaire): self
    {
        if ($this->comptePartenaire->contains($comptePartenaire)) {
            $this->comptePartenaire->removeElement($comptePartenaire);
            // set the owning side to null (unless already changed)
            if ($comptePartenaire->getPartenaire() === $this) {
                $comptePartenaire->setPartenaire(null);
            }
        }

        return $this;
    }
}
