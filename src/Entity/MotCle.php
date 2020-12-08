<?php

namespace App\Entity;

use App\Repository\MotCleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=MotCleRepository::class)
 */
class MotCle
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $mot_cle;

    /**
     * @Gedmo\Slug(fields={"mot_cle"})
     * @ORM\Column(length=70, unique=true)
     */
    private $slug;

    /**
     * @ORM\ManyToMany(targetEntity=Article::class, mappedBy="mot_cle")
     */
    private $articles;

    /**
     * @var \DateTime $created_at
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @var \DateTime $updated_at
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    public function __toString(): ?string
    {
        return $this->mot_cle;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMotCle(): ?string
    {
        return $this->mot_cle;
    }

    public function setMotCle(string $mot_cle): self
    {
        $this->mot_cle = $mot_cle;
        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->addMotCle($this);
        }
        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            $article->removeMotCle($this);
        }
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

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
