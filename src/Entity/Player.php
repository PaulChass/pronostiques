<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlayerRepository::class)
 */
class Player
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class, inversedBy="players")
     * @ORM\JoinColumn(nullable=false)
     */
    private $team;

    /**
     * @ORM\Column(type="array")
     */
    private $Stats = [];

    /**
     * @ORM\Column(type="array")
     */
    private $Stats5 = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $last5Games = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): self
    {
        $this->team = $team;

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
        return $this->Stats5;
    }

    public function setStats5(array $Stats5): self
    {
        $this->Stats5 = $Stats5;

        return $this;
    }

    public function getLast5Games(): ?array
    {
        return $this->last5Games;
    }

    public function setLast5Games(?array $last5Games): self
    {
        $this->last5Games = $last5Games;

        return $this;
    }
}
