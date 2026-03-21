<?php

namespace App\Entity;

use App\Repository\ExerciceRespirationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ExerciceRespirationRepository::class)]
class ExerciceRespiration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Le nom de la série est obligatoire.')]
    private ?string $nameSeries = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "La durée d'inspiration est obligatoire.")]
    #[Assert\Positive(message: "L'inspiration doit être supérieure à 0.")]
    private ?int $timeInspiration = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "La durée d'apnée est obligatoire.")]
    #[Assert\PositiveOrZero(message: "L'apnée ne peut pas être négative.")]
    private ?int $timeApnea = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "La durée d'expiration est obligatoire.")]
    #[Assert\Positive(message: "L'expiration doit être supérieure à 0.")]
    private ?int $timeExpiration = null;

    #[ORM\Column]
    private ?bool $isPredefini = null;

    #[ORM\ManyToOne]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameSeries(): ?string
    {
        return $this->nameSeries;
    }

    public function setNameSeries(string $nameSeries): static
    {
        $this->nameSeries = $nameSeries;
        return $this;
    }

    public function getTimeInspiration(): ?int
    {
        return $this->timeInspiration;
    }

    public function setTimeInspiration(int $timeInspiration): static
    {
        $this->timeInspiration = $timeInspiration;
        return $this;
    }

    public function getTimeApnea(): ?int
    {
        return $this->timeApnea;
    }

    public function setTimeApnea(int $timeApnea): static
    {
        $this->timeApnea = $timeApnea;
        return $this;
    }

    public function getTimeExpiration(): ?int
    {
        return $this->timeExpiration;
    }

    public function setTimeExpiration(int $timeExpiration): static
    {
        $this->timeExpiration = $timeExpiration;
        return $this;
    }

    public function isPredefini(): ?bool
    {
        return $this->isPredefini;
    }

    public function setIsPredefini(bool $isPredefini): static
    {
        $this->isPredefini = $isPredefini;

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
}