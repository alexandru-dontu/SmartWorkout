<?php

namespace App\Entity;

use App\Repository\UserWeightsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserWeightsRepository::class)]
class UserWeights
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'weights')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?float $weight = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateRecorded = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __construct()
    {
        $this->dateRecorded = new \DateTimeImmutable();
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function getDateRecorded(): ?\DateTimeImmutable
    {
        return $this->dateRecorded;
    }

    public function setDateRecorded(\DateTimeImmutable $dateRecorded): self
    {
        $this->dateRecorded = $dateRecorded;
        return $this;
    }
}
