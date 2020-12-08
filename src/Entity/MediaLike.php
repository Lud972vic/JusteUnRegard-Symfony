<?php

namespace App\Entity;

use App\Repository\MediaLikeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MediaLikeRepository::class)
 */
class MediaLike
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Media::class, inversedBy="mediaLikes")
     */
    private $media;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="mediaLikes")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMedia(): ?Media
    {
        return $this->media;
    }

    public function setMedia(?Media $media): self
    {
        $this->media = $media;

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
}
