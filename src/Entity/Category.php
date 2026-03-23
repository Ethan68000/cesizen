<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $name = null;

    /**
     * @var Collection<int, Informations>
     */
    #[ORM\ManyToMany(targetEntity: Informations::class, mappedBy: 'categories')]
    private Collection $informations;

    public function __toString(): string
    {
        return $this->name ?? '';
    }

    public function __construct()
    {
        $this->informations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Informations>
     */
    public function getInformations(): Collection
    {
        return $this->informations;
    }

    public function addInformation(Informations $information): static
    {
        if (!$this->informations->contains($information)) {
            $this->informations->add($information);
            $information->addCategory($this);
        }

        return $this;
    }

    public function removeInformation(Informations $information): static
    {
        if ($this->informations->removeElement($information)) {
            $information->removeCategory($this);
        }

        return $this;
    }
}