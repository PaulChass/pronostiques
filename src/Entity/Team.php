<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TeamRepository::class)
 */
class Team
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
    private $logourl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $twitter;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $injuries = [];

    /**
     * @ORM\Column(type="datetime")
     */
    private $last_update;

    /**
     * @ORM\OneToMany(targetEntity=Player::class, mappedBy="team")
     */
    private $players;

    /**
     * @ORM\Column(type="array")
     */
    private $Stats = [];

    /**
     * @ORM\Column(type="array")
     */
    private $stats5 = [];

    /**
     * @ORM\Column(type="array")
     */
    private $statsLocation = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $last5games = [];

    /**
     * @ORM\Column(type="array")
     */
    private $advancedStats = [];

    /**
     * @ORM\Column(type="integer")
     */
    private $teamId;

    public function __construct()
    {
        $this->players = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogourl(): ?string
    {
        return $this->logourl;
    }

    public function setLogourl(string $logourl): self
    {
        $this->logourl = $logourl;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter): self
    {
        $this->twitter = $twitter;

        return $this;
    }

    public function getInjuries(): ?array
    {
        return $this->injuries;
    }

    public function setInjuries(?array $injuries): self
    {
        $this->injuries = $injuries;

        return $this;
    }

    public function getLastUpdate(): ?\DateTimeInterface
    {
        return $this->last_update;
    }

    public function setLastUpdate(\DateTimeInterface $last_update): self
    {
        $this->last_update = $last_update;

        return $this;
    }

    /**
     * @return Collection|Player[]
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): self
    {
        if (!$this->players->contains($player)) {
            $this->players[] = $player;
            $player->setTeam($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        if ($this->players->contains($player)) {
            $this->players->removeElement($player);
            // set the owning side to null (unless already changed)
            if ($player->getTeam() === $this) {
                $player->setTeam(null);
            }
        }

        return $this;
    }

    public function getStats(): ?array
    {
        return $this->Stats;
    }

    public function setStats(array $Stats): self
    {
        $this->Stats = $Stats;

        return $this;
    }

    public function getStats5(): ?array
    {
        return $this->stats5;
    }

    public function setStats5(array $stats5): self
    {
        $this->stats5 = $stats5;

        return $this;
    }

    public function getStatsLocation(): ?array
    {
        return $this->statsLocation;
    }

    public function setStatsLocation(array $statsLocation): self
    {
        $this->statsLocation = $statsLocation;

        return $this;
    }

    public function getLast5games(): ?array
    {
        return $this->last5games;
    }

    public function setLast5games(array $last5games): self
    {
        $this->last5games = $last5games;

        return $this;
    }

    public function getAdvancedStats(): ?array
    {
        return $this->advancedStats;
    }

    public function setAdvancedStats(array $advancedStats): self
    {
        $this->advancedStats = $advancedStats;

        return $this;
    }

    public function getTeamId(): ?int
    {
        return $this->teamId;
    }

    public function setTeamId(int $teamId): self
    {
        $this->teamId = $teamId;

        return $this;
    }
}
