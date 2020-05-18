<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datetime;

    /**
     * @ORM\Column(type="integer")
     */
    private $awayteamid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $odds;

    /**
     * @ORM\Column(type="integer")
     */
    private $hometeamid;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="gameId", orphanRemoval=true)
     */
    private $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatetime(): ?\DateTimeInterface
    {
        return $this->datetime;
    }

    public function setDatetime(\DateTimeInterface $datetime): self
    {
        $this->datetime = $datetime;

        return $this;
    }

    public function getAwayteamid(): ?int
    {
        return $this->awayteamid;
    }

    public function setAwayteamid(int $awayteamid): self
    {
        $this->awayteamid = $awayteamid;

        return $this;
    }

    public function getOdds(): ?string
    {
        return $this->odds;
    }

    public function setOdds(?string $odds): self
    {
        $this->odds = $odds;

        return $this;
    }

    public function getHometeamid(): ?int
    {
        return $this->hometeamid;
    }

    public function setHometeamid(int $hometeamid): self
    {
        $this->hometeamid = $hometeamid;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setGameId($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getGameId() === $this) {
                $comment->setGameId(null);
            }
        }

        return $this;
    }
}
