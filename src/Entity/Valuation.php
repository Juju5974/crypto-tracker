<?php

namespace App\Entity;

use App\Repository\ValuationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ValuationRepository::class)]
class Valuation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $delta = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDelta(): ?string
    {
        return $this->delta;
    }

    public function setDelta(string $delta): self
    {
        $this->delta = $delta;

        return $this;
    }
}
