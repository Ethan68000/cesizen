<?php

namespace App\Entity;

use App\Repository\ExerciceRespirationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExerciceRespirationRepository::class)]
class ExerciceRespiration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nameSeries = null;

    #[ORM\Column]
    private ?int $timeInspiration = null;

    #[ORM\Column]
    private ?int $timeApnea = null;

    #[ORM\Column]
    private ?int $timeExpiration = null;

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
}
