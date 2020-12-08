<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */

class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Length(
     *      min = 10,
     *      max = 50,
     *      allowEmptyString = false
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="user", orphanRemoval=true)
     */
    private $articles;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      allowEmptyString = false
     * )
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      allowEmptyString = false
     * )
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      allowEmptyString = false
     * )
     */
    private $pseudo;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_naissance;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Length(
     *      min = 25,
     *      allowEmptyString = true
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\Length(
     *      min = 10,
     *      max = 12,
     *      allowEmptyString = true
     * )
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *      min = 30,
     *      allowEmptyString = true
     * )
     */
    private $url_compte_instagram;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *      min = 30,
     *      allowEmptyString = true
     * )
     */
    private $url_compte_facebook;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *      min = 30,
     *      allowEmptyString = true
     * )
     */
    private $url_compte_autre;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Assert\Length(
     *      min = 7,
     *      max = 18,
     *      allowEmptyString = true
     * )
     */
    private $ip;

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
     * @ORM\ManyToOne(targetEntity=Ville::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ville;

    /**
     * @ORM\ManyToOne(targetEntity=Civilite::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $civilite;

    /**
     * @ORM\OneToMany(targetEntity=Media::class, mappedBy="user", orphanRemoval=true)
     */
    private $media;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="users")
     */
    private $profil;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="user", orphanRemoval=true)
     */
    private $commentaires;

    /**
     * @ORM\OneToMany(targetEntity=MediaLike::class, mappedBy="user", orphanRemoval=true)
     */
    private $mediaLikes;

    /**
     * @ORM\OneToMany(targetEntity=AccessoirePublicite::class, mappedBy="user")
     */
    private $accessoirePublicites;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->media = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->mediaLikes = new ArrayCollection();
        $this->accessoirePublicites = new ArrayCollection();
    }

    public function __toString(): ?string
    {
        return $this->email;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
            $article->setUser($this);
        }
        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getUser() === $this) {
                $article->setUser(null);
            }
        }
        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->date_naissance;
    }

    public function setDateNaissance(?\DateTimeInterface $date_naissance): self
    {
        $this->date_naissance = $date_naissance;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getUrlCompteInstagram(): ?string
    {
        return $this->url_compte_instagram;
    }

    public function setUrlCompteInstagram(?string $url_compte_instagram): self
    {
        $this->url_compte_instagram = $url_compte_instagram;

        return $this;
    }

    public function getUrlCompteFacebook(): ?string
    {
        return $this->url_compte_facebook;
    }

    public function setUrlCompteFacebook(?string $url_compte_facebook): self
    {
        $this->url_compte_facebook = $url_compte_facebook;

        return $this;
    }

    public function getUrlCompteAutre(): ?string
    {
        return $this->url_compte_autre;
    }

    public function setUrlCompteAutre(?string $url_compte_autre): self
    {
        $this->url_compte_autre = $url_compte_autre;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(?string $ip): self
    {
        $this->ip = $ip;

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

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function getCivilite(): ?Civilite
    {
        return $this->civilite;
    }

    public function setCivilite(?Civilite $civilite): self
    {
        $this->civilite = $civilite;

        return $this;
    }

    /**
     * @return Collection|Media[]
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedium(Media $medium): self
    {
        if (!$this->media->contains($medium)) {
            $this->media[] = $medium;
            $medium->setUser($this);
        }

        return $this;
    }

    public function removeMedium(Media $medium): self
    {
        if ($this->media->contains($medium)) {
            $this->media->removeElement($medium);
            // set the owning side to null (unless already changed)
            if ($medium->getUser() === $this) {
                $medium->setUser(null);
            }
        }

        return $this;
    }

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

        return $this;
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setUser($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->contains($commentaire)) {
            $this->commentaires->removeElement($commentaire);
            // set the owning side to null (unless already changed)
            if ($commentaire->getUser() === $this) {
                $commentaire->setUser(null);
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
            $mediaLike->setUser($this);
        }

        return $this;
    }

    public function removeMediaLike(MediaLike $mediaLike): self
    {
        if ($this->mediaLikes->contains($mediaLike)) {
            $this->mediaLikes->removeElement($mediaLike);
            // set the owning side to null (unless already changed)
            if ($mediaLike->getUser() === $this) {
                $mediaLike->setUser(null);
            }
        }

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
            $accessoirePublicite->setUser($this);
        }

        return $this;
    }

    public function removeAccessoirePublicite(AccessoirePublicite $accessoirePublicite): self
    {
        if ($this->accessoirePublicites->contains($accessoirePublicite)) {
            $this->accessoirePublicites->removeElement($accessoirePublicite);
            // set the owning side to null (unless already changed)
            if ($accessoirePublicite->getUser() === $this) {
                $accessoirePublicite->setUser(null);
            }
        }

        return $this;
    }

}
