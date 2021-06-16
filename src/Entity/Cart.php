<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;

/**
 * @ORM\Entity(repositoryClass=CartRepository::class)
 */
class Cart
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     *@ORM\ManyToMany(targetEntity="CartEntry")
     * @JoinTable(name="carts_cartentries",
     *      joinColumns={@JoinColumn(name="cart_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="cartentry_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $cartEntry;

    /**
     * @ORM\Column(type="decimal", precision=16, scale=2, nullable=true)
     */
    private $totalPrice;

    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="cart")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    public function __construct()
    {
        $this->cartEntry = new ArrayCollection();
        $this->user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotalPrice(): ?string
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(string $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    /**
     * @return Collection|CartEntry[]
     */
    public function getCartEntry(): Collection
    {
        return $this->cartEntry;
    }

    public function addCartEntry(CartEntry $cartEntry): self
    {
        if (!$this->cartEntry->contains($cartEntry)) {
            $this->cartEntry[] = $cartEntry;
        }

        return $this;
    }

    public function removeCartEntry(CartEntry $cartEntry): self
    {
        $this->cartEntry->removeElement($cartEntry);

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

   
}
