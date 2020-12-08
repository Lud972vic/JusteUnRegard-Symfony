<?php

namespace App\Entity;

use App\Repository\TypeAnnonceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeAnnonceRepository::class)
 */
class TypeAnnonce
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $libelle;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity=AccessoirePublicite::class, mappedBy="typeannonce")
     */
    private $accessoirePublicites;

    public function __construct()
    {
        $this->accessoirePublicites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection|AccessoirePublicite[]
     */
    public function getAccessoirePublicites(): Collection
    {
        return $this->accessoirePublicites;
    }

    public function addAccessoirePublicite(AccessoirePublicite $accessoirePublicite): self
    {
        if (!$this->accessoirePublicites->contains($accessoirePublicite)) {
            $this->accessoirePublicites[] = $accessoirePublicite;
            $accessoirePublicite->setTypeannonce($this);
        }

        return $this;
    }

    public function removeAccessoirePublicite(AccessoirePublicite $accessoirePublicite): self
    {
        if ($this->accessoirePublicites->contains($accessoirePublicite)) {
            $this->accessoirePublicites->removeElement($accessoirePublicite);
            // set the owning side to null (unless already changed)
            if ($accessoirePublicite->getTypeannonce() === $this) {
                $accessoirePublicite->setTypeannonce(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->libelle;
    }
}
