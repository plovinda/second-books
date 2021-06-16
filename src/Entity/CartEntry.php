<?php

namespace App\Entity;

use App\Repository\CartEntryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 *
 * @ORM\Entity(repositoryClass="App\Repository\CartEntryRepository", repositoryClass=CartEntryRepository::class)
 */
class CartEntry
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $basePrice;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $totalPrice;

    /**
     * @ORM\ManyToMany(targetEntity="Book")
     * @JoinTable(name="cartentry_book",
     *      joinColumns={@JoinColumn(name="cartentry_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="book_id", referencedColumnName="id", unique=false)}
     *      )
     */
    private $book_id;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getBasePrice(): ?string
    {
        return $this->basePrice;
    }

    public function setBasePrice(string $basePrice): self
    {
        $this->basePrice = $basePrice;

        return $this;
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
    

    private $name = '';

    public function __construct()
    {
        $this->book_id = new ArrayCollection();
    } // initialize $name as an empty string

    public function __toString()
    {
        return $this->name; // which is a string in any circumstance
    }

    public function addBookId(Book $bookId): self
    {
        if (!$this->book_id->contains($bookId)) {
            $this->book_id[] = $bookId;
        }

        return $this;
    }

    public function removeBookId(Book $bookId): self
    {
        $this->book_id->removeElement($bookId);

        return $this;
    }

    /**
     * @return Collection|Book[]
     */
    public function getBookId(): Collection
    {
        return $this->book_id;
    }


}
