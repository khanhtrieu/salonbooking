<?php

namespace App\Entity;

use App\Repository\ShopServiceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ShopServiceRepository::class)
 */
class ShopService
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Shop::class, inversedBy="Service")
     */
    private $Shop;

    /**
     * @ORM\ManyToOne(targetEntity=Services::class, inversedBy="Services")
     */
    private $Service;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShop(): ?Shop
    {
        return $this->Shop;
    }

    public function setShop(?Shop $Shop): self
    {
        $this->Shop = $Shop;

        return $this;
    }

    public function getService(): ?Services
    {
        return $this->Service;
    }

    public function setService(?Services $Service): self
    {
        $this->Service = $Service;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->Price;
    }

    public function setPrice(float $Price): self
    {
        $this->Price = $Price;

        return $this;
    }
}
