<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $categoryName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $categoryDescription;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Book",mappedBy="category")
     */

    private $book;

    public function __construct()
    {
        $this->bookId = new ArrayCollection();
        $this->book = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategoryName(): ?string
    {
        return $this->categoryName;
    }

    public function setCategoryName(string $categoryName): self
    {
        $this->categoryName = $categoryName;

        return $this;
    }

    public function getCategoryDescription(): ?string
    {
        return $this->categoryDescription;
    }

    public function setCategoryDescription(string $categoryDescription): self
    {
        $this->categoryDescription = $categoryDescription;

        return $this;
    }

    /**
     * @return Collection|Book[]
     */
    public function getBookId(): Collection
    {
        return $this->bookId;
    }

    public function addBookId(Book $bookId): self
    {
        if (!$this->bookId->contains($bookId)) {
            $this->bookId[] = $bookId;
            $bookId->setCategoryId($this);
        }

        return $this;
    }

    public function removeBookId(Book $bookId): self
    {
        if ($this->bookId->removeElement($bookId)) {
            // set the owning side to null (unless already changed)
            if ($bookId->getCategoryId() === $this) {
                $bookId->setCategoryId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Book[]
     */
    public function getBook(): Collection
    {
        return $this->book;
    }

    public function addBook(Book $book): self
    {
        if (!$this->book->contains($book)) {
            $this->book[] = $book;
            $book->setCategory($this);
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        if ($this->book->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getCategory() === $this) {
                $book->setCategory(null);
            }
        }

        return $this;
    }

    public function __toString() {
        if(is_null($this->categoryName)) {
            return 'NULL';
        }
        return $this->categoryName;
    }
}
