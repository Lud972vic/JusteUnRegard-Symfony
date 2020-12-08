<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 * @Vich\Uploadable
 */
class Article
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
    private $titre;

    /**
     * @Gedmo\Slug(fields={"titre"})
     * @ORM\Column(length=255, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     */
    private $contenu;

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

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="featured_images", fileNameProperty="image")
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="article", orphanRemoval=true)
     */
    private $commentaire;

    /**
     * @ORM\ManyToMany(targetEntity=MotCle::class, inversedBy="articles")
     */
    private $mot_cle;

    /**
     * @ORM\ManyToMany(targetEntity=Categorie::class, inversedBy="articles")
     */
    private $categorie;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $visible;

    public function __construct()
    {
        $this->commentaire = new ArrayCollection();
        $this->mot_cle = new ArrayCollection();
        $this->categorie = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;
        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;
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

    // public function getImage()
    // {
    //     return $this->image;
    // }

    // public function setImage($image)
    // {
    //     $this->image = $image;
    //     return $this;
    // }

    // public function getImageFile()
    // {
    //     return $this->imageFile;
    // }

    // public function setImageFile(File $image = null)
    // {
    //     $this->imageFile = $image;
    //     if ($image) {
    //         $this->updated_at = new \DateTime('now');
    //     }
    // }

/**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }




    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaire(): Collection
    {
        return $this->commentaire;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaire->contains($commentaire)) {
            $this->commentaire[] = $commentaire;
            $commentaire->setArticle($this);
        }
        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaire->contains($commentaire)) {
            $this->commentaire->removeElement($commentaire);
            // set the owning side to null (unless already changed)
            if ($commentaire->getArticle() === $this) {
                $commentaire->setArticle(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection|MotCle[]
     */
    public function getMotCle(): Collection
    {
        return $this->mot_cle;
    }

    public function addMotCle(MotCle $motCle): self
    {
        if (!$this->mot_cle->contains($motCle)) {
            $this->mot_cle[] = $motCle;
        }
        return $this;
    }

    public function removeMotCle(MotCle $motCle): self
    {
        if ($this->mot_cle->contains($motCle)) {
            $this->mot_cle->removeElement($motCle);
        }
        return $this;
    }

    /**
     * @return Collection|Categorie[]
     */
    public function getCategorie(): Collection
    {
        return $this->categorie;
    }

    public function addCategorie(Categorie $categorie): self
    {
        if (!$this->categorie->contains($categorie)) {
            $this->categorie[] = $categorie;
        }
        return $this;
    }

    public function removeCategorie(Categorie $categorie): self
    {
        if ($this->categorie->contains($categorie)) {
            $this->categorie->removeElement($categorie);
        }
        return $this;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(?bool $visible): self
    {
        $this->visible = $visible;

        return $this;
    }
}
