<?php

namespace App\Entity;

use App\Repository\ShopRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ShopRepository::class)
 */
class Shop
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $Name;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $Address1;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $Address2;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $City;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $State;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $ZipCode;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $Country;

    /**
     * @ORM\Column(type="binary")
     */
    private $Active;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Description;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getAddress1(): ?string
    {
        return $this->Address1;
    }

    public function setAddress1(?string $Address1): self
    {
        $this->Address1 = $Address1;

        return $this;
    }

    public function getAddress2(): ?string
    {
        return $this->Address2;
    }

    public function setAddress2(?string $Address2): self
    {
        $this->Address2 = $Address2;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->City;
    }

    public function setCity(?string $City): self
    {
        $this->City = $City;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->State;
    }

    public function setState(?string $State): self
    {
        $this->State = $State;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->ZipCode;
    }

    public function setZipCode(?string $ZipCode): self
    {
        $this->ZipCode = $ZipCode;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->Country;
    }

    public function setCountry(?string $Country): self
    {
        $this->Country = $Country;

        return $this;
    }

    public function getActive()
    {
        return $this->Active;
    }

    public function setActive($Active): self
    {
        $this->Active = $Active;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }
}
