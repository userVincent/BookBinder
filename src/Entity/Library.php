<?php

namespace App\Entity;

use App\Repository\LibraryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LibraryRepository::class)]
class Library
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 8)]
    private ?string $longitude = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 8)]
    private ?string $latitude = null;

    #[ORM\ManyToMany(targetEntity: Book::class, inversedBy: 'libraries')]
    private Collection $books;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'libraries')]
    private Collection $members;

    #[ORM\OneToMany(mappedBy: 'library', targetEntity: Meetup::class)]
    private Collection $meetups;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Town = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Year = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Type = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $StreetName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $HouseNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $PostalCode = null;

    public function __construct()
    {
        $this->books = new ArrayCollection();
        $this->members = new ArrayCollection();
        $this->meetups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): self
    {
        if (!$this->books->contains($book)) {
            $this->books->add($book);
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        $this->books->removeElement($book);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(User $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members->add($member);
        }

        return $this;
    }

    public function removeMember(User $member): self
    {
        $this->members->removeElement($member);

        return $this;
    }

    /**
     * @return Collection<int, Meetup>
     */
    public function getMeetups(): Collection
    {
        return $this->meetups;
    }

    public function addMeetup(Meetup $meetup): self
    {
        if (!$this->meetups->contains($meetup)) {
            $this->meetups->add($meetup);
            $meetup->setLibrary($this);
        }

        return $this;
    }

    public function removeMeetup(Meetup $meetup): self
    {
        if ($this->meetups->removeElement($meetup)) {
            // set the owning side to null (unless already changed)
            if ($meetup->getLibrary() === $this) {
                $meetup->setLibrary(null);
            }
        }

        return $this;
    }

    public function getTown(): ?string
    {
        return $this->Town;
    }

    public function setTown(?string $Town): self
    {
        $this->Town = $Town;

        return $this;
    }

    public function getYear(): ?string
    {
        return $this->Year;
    }

    public function setYear(?string $Year): self
    {
        $this->Year = $Year;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->Type;
    }

    public function setType(?string $Type): self
    {
        $this->Type = $Type;

        return $this;
    }

    public function getStreetName(): ?string
    {
        return $this->StreetName;
    }

    public function setStreetName(?string $StreetName): self
    {
        $this->StreetName = $StreetName;

        return $this;
    }

    public function getHouseNumber(): ?string
    {
        return $this->HouseNumber;
    }

    public function setHouseNumber(?string $HouseNumber): self
    {
        $this->HouseNumber = $HouseNumber;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->PostalCode;
    }

    public function setPostalCode(?string $PostalCode): self
    {
        $this->PostalCode = $PostalCode;

        return $this;
    }
}
