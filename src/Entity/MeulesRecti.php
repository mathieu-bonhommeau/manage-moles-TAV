<?php

namespace App\Entity;

use App\Repository\MeulesRectiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MeulesRectiRepository::class)
 */
class MeulesRecti
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ref;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $designationTAV;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $grain;

    /**
     * @ORM\Column(type="integer")
     */
    private $diametre;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $hauteur;

    /**
     * @ORM\Column(type="integer")
     */
    private $stock;

    /**
     * @ORM\ManyToOne(targetEntity=Fournisseur::class, inversedBy="meulesRectis")
     */
    private $fournisseur;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nonLivres;

    /**
     * @ORM\OneToMany(targetEntity=Position::class, mappedBy="meulesRecti")
     */
    private $positions;

    public function __construct()
    {
        $this->positions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(?string $ref): self
    {
        $this->ref = $ref;

        return $this;
    }

    public function getDesignationTAV(): ?string
    {
        return $this->designationTAV;
    }

    public function setDesignationTAV(string $designationTAV): self
    {
        $this->designationTAV = $designationTAV;

        return $this;
    }

    public function getGrain(): ?string
    {
        return $this->grain;
    }

    public function setGrain(?string $grain): self
    {
        $this->grain = $grain;

        return $this;
    }

    public function getDiametre(): ?int
    {
        return $this->diametre;
    }

    public function setDiametre(int $diametre): self
    {
        $this->diametre = $diametre;

        return $this;
    }

    public function getHauteur(): ?int
    {
        return $this->hauteur;
    }

    public function setHauteur(?int $hauteur): self
    {
        $this->hauteur = $hauteur;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getFournisseur(): ?Fournisseur
    {
        return $this->fournisseur;
    }

    public function setFournisseur(?Fournisseur $fournisseur): self
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }

    public function getNonLivres(): ?int
    {
        return $this->nonLivres;
    }

    public function setNonLivres(?int $nonLivres): self
    {
        $this->nonLivres = $nonLivres;

        return $this;
    }

    /**
     * @return Collection|Position[]
     */
    public function getPositions(): Collection
    {
        return $this->positions;
    }

    public function addPosition(Position $position): self
    {
        if (!$this->positions->contains($position)) {
            $this->positions[] = $position;
            $position->setMeulesRecti($this);
        }

        return $this;
    }

    public function removePosition(Position $position): self
    {
        if ($this->positions->removeElement($position)) {
            // set the owning side to null (unless already changed)
            if ($position->getMeulesRecti() === $this) {
                $position->setMeulesRecti(null);
            }
        }

        return $this;
    }
}
