<?php

namespace App\Entity;

use App\Repository\CurrencyRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

#[ORM\Entity(repositoryClass: CurrencyRepository::class)]
class Currency
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private string $id;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private ?string $currency_code = null;

    #[ORM\Column]
    private ?float $exchange_rate = null;

    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
    }

    public function getId(): string
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

    public function getCurrencyCode(): ?string
    {
        return $this->currency_code;
    }

    public function setCurrencyCode(string $currency_code): static
    {
        $this->currency_code = $currency_code;

        return $this;
    }

    public function getExchangeRate(): ?float
    {
        return $this->exchange_rate;
    }

    public function setExchangeRate(float $exchange_rate): static
    {
        $this->exchange_rate = $exchange_rate;

        return $this;
    }
}
