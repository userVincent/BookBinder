<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $ISBN = null;


    #[ORM\ManyToMany(targetEntity: Library::class, mappedBy: 'books')]
    private Collection $libraries;

    #[ORM\OneToMany(mappedBy: 'books', targetEntity: UserFavoriteBook::class, cascade: ['persist', 'remove'])]
    private Collection $userFavoriteBooks;

    public function __construct()
    {
        $this->libraries = new ArrayCollection();
        $this->userFavoriteBooks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getISBN(): ?string
    {
        return $this->ISBN;
    }

    public function setISBN(string $ISBN): self
    {
        $this->ISBN = $ISBN;

        return $this;
    }


    /**
     * @return Collection<int, Library>
     */
    public function getLibraries(): Collection
    {
        return $this->libraries;
    }

    public function addLibrary(Library $library): self
    {
        if (!$this->libraries->contains($library)) {
            $this->libraries->add($library);
            $library->addBook($this);
        }

        return $this;
    }

    public function removeLibrary(Library $library): self
    {
        if ($this->libraries->removeElement($library)) {
            $library->removeBook($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, UserFavoriteBook>
     */
    public function getUserFavoriteBooks(): Collection
    {
        return $this->userFavoriteBooks;
    }

    public function addUserFavoriteBook(UserFavoriteBook $userFavoriteBook): self
    {
        if (!$this->userFavoriteBooks->contains($userFavoriteBook)) {
            $this->userFavoriteBooks[] = $userFavoriteBook;
            $userFavoriteBook->setBook($this);
        }

        return $this;
    }

    public function removeUserFavoriteBook(UserFavoriteBook $userFavoriteBook): self
    {
        if ($this->userFavoriteBooks->removeElement($userFavoriteBook)) {
            // set the owning side to null (unless already changed)
            if ($userFavoriteBook->getBook() === $this) {
                $userFavoriteBook->setBook(null);
            }
        }

        return $this;
    }
}
