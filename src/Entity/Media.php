<?php

namespace App\Entity;

use App\Entity\Categorie;
use App\Entity\Commentaire;
use App\Entity\MediaLike;
use App\Entity\User;
use App\Repository\MediaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=MediaRepository::class)
 * @Vich\Uploadable
 */
class Media
{
    public function __construct()
    {
        $this->created_at = new \Datetime();
        $this->updated_at = new \Datetime();
        $this->commentaire = new ArrayCollection();
        $this->mediaLikes = new ArrayCollection();
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $taille;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $type_fichier;

    /**
     * @ORM\Column(type="string", length=8, nullable=true)
     */
    private $dure;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $notorite;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $banni;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $banni_par_user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTimeInterface|null
     */
    private $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="media")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTaille(): ?string
    {
        return $this->taille;
    }

    public function setTaille(?string $taille): self
    {
        $this->taille = $this->GetSizeName($taille);
        return $this;
    }

    public function getTypeFichier(): ?string
    {
        return $this->type_fichier;
    }

    public function setTypeFichier(?string $type_fichier): self
    {
        $this->type_fichier = $type_fichier;

        return $this;
    }

    public function getDure(): ?string
    {
        return $this->dure;
    }

    public function setDure(?string $dure): self
    {
        $this->dure = $dure;

        return $this;
    }

    public function getNotorite(): ?int
    {
        return $this->notorite;
    }

    public function setNotorite(?int $notorite): self
    {
        $this->notorite = $notorite;

        return $this;
    }

    public function getBanni(): ?bool
    {
        return $this->banni;
    }

    public function setBanni(?bool $banni): self
    {
        $this->banni = $banni;

        return $this;
    }

    public function getBanniParUser(): ?string
    {
        return $this->banni_par_user;
    }

    public function setBanniParUser(?string $banni_par_user): self
    {
        $this->banni_par_user = $banni_par_user;

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
     * @Vich\UploadableField(mapping="featured_images", fileNameProperty="nom", size="taille", mimeType="type_fichier")
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="media")
     */
    private $categorie;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="media", orphanRemoval=true)
     */
    private $commentaire;

    /**
     * @ORM\OneToMany(targetEntity=MediaLike::class, mappedBy="media", orphanRemoval=true)
     */
    private $mediaLikes;

    // /**
    //  * @ORM\Column(type="string")
    //  *
    //  * @var string|null
    //  */
    // private $imageName;

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
        return $this->imageFile;
    }

    public function setImage(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function setImageSize(?int $imageSize): void
    {

        $this->taille = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    public static function GetSizeName($octet)
    {
        $unite = array(' octet', ' Ko', ' Mo', ' Go');

        if ($octet < 1000) {
            return $octet . $unite[0];
        } else {
            if ($octet < 1000000) {
                $ko = round($octet / 1024, 2);
                return $ko . $unite[1];
            } else {
                if ($octet < 1000000000) {
                    $mo = round($octet / (1024 * 1024), 2);
                    return $mo . $unite[2];
                } else {
                    $go = round($octet / (1024 * 1024 * 1024), 2);
                    return $go . $unite[3];
                }
            }
        }
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getcommentaire(): Collection
    {
        return $this->commentaire;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaire->contains($commentaire)) {
            $this->commentaire[] = $commentaire;
            $commentaire->setMedia($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaire->contains($commentaire)) {
            $this->commentaire->removeElement($commentaire);
            // set the owning side to null (unless already changed)
            if ($commentaire->getMedia() === $this) {
                $commentaire->setMedia(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MediaLike[]
     */
    public function getMediaLikes(): Collection
    {
        return $this->mediaLikes;
    }

    public function addMediaLike(MediaLike $mediaLike): self
    {
        if (!$this->mediaLikes->contains($mediaLike)) {
            $this->mediaLikes[] = $mediaLike;
            $mediaLike->setMedia($this);
        }

        return $this;
    }

    public function removeMediaLike(MediaLike $mediaLike): self
    {
        if ($this->mediaLikes->contains($mediaLike)) {
            $this->mediaLikes->removeElement($mediaLike);
            // set the owning side to null (unless already changed)
            if ($mediaLike->getMedia() === $this) {
                $mediaLike->setMedia(null);
            }
        }

        return $this;
    }

    /**
     * Permet de savoir si ce média est liké par un utilisateur
     *
     * @param User $user
     * @return boolean
     */
    public function isLikedByUser(User $user): bool
    {
        foreach ($this->mediaLikes as $mediaLike) {
            if ($mediaLike->getUser() === $user) {
                return true;
            }
        }
        return false;
    }
}
