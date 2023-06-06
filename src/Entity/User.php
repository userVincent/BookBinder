<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Repository\MeetupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;



#[ORM\Entity(repositoryClass: UserRepository::class)]
#[Vich\Uploadable]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $LastName = null;

    #[ORM\Column(length: 255)]
    private ?string $FirstName = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Birthday = null;

    #[ORM\Column(length: 255)]
    private ?string $Address = null;

    #[ORM\ManyToMany(targetEntity: Library::class, mappedBy: 'members')]
    private Collection $libraries;

    #[ORM\OneToMany(mappedBy: 'person', targetEntity: Meetup::class)]
    private Collection $meetups;

    /**
     * Many Users have Many Groups.
     * @var Collection<Book>
     */
    #[ORM\ManyToMany(targetEntity: Book::class, inversedBy: 'users')]
    #[ORM\JoinTable(name: 'user_favorite_books')]
    private Collection $favoriteBooks;

    #[Assert\File(
        maxSize: '5M',
        mimeTypes: ['image/jpeg', 'image/png'],
        mimeTypesMessage: 'Please upload a valid JPG or PNG image'
    )]
    #[Vich\UploadableField(mapping: 'users', fileNameProperty: 'profilepicFilename', size: 'profilepicSize')]
    private ?File $profilepicFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profilepicFilename = null;


    #[ORM\Column(nullable: true)]
    private ?int $profilepicSize = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->libraries = new ArrayCollection();
        $this->meetups = new ArrayCollection();
        $this->favoriteBooks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastName(): ?string
    {
        return $this->LastName;
    }

    public function setLastName(string $LastName): self
    {
        $this->LastName = $LastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->FirstName;
    }

    public function setFirstName(string $FirstName): self
    {
        $this->FirstName = $FirstName;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->Birthday;
    }

    public function setBirthday(\DateTimeInterface $Birthday): self
    {
        $this->Birthday = $Birthday;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->Address;
    }

    public function setAddress(string $Address): self
    {
        $this->Address = $Address;

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
            $library->addMember($this);
        }

        return $this;
    }

    public function removeLibrary(Library $library): self
    {
        if ($this->libraries->removeElement($library)) {
            $library->removeMember($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Meetup>
     */
    public function getMeetups(): Collection
    {
        return $this->meetups;
    }

    public function addMeetup(Meetup $meetup, User $person2, Library $library, \DateTimeInterface $date, \DateTimeInterface $time): self
    {
        if (!$this->meetups->contains($meetup)) {
            $this->meetups->add($meetup);
            $meetup->setPerson1($this);
            $meetup->setPerson2($person2);
            $meetup->setLibrary($library);
            $meetup->setDate($date);
            $meetup->setTime($time);
        }

        return $this;
    }

    public function removeMeetupLocally(Meetup $meetup): self
    {
        if ($this->meetups->removeElement($meetup)) {
            // set the owning side to null (unless already changed)
            if ($meetup->getPerson1() === $this) {
                $meetup->setPerson1(null);
            }else if ($meetup->getPerson2() === $this) {
                $meetup->setPerson2(null);
            }
        }

        return $this;
    }

    public function removeMeetup(Meetup $meetup, MeetupRepository $meetupRepository): self
    {
        if ($this->meetups->removeElement($meetup)) {
            // set the owning side to null (unless already changed)
            if ($meetup->getPerson1() === $this) {
                $meetup->getPerson2()->removeMeetupLocally($meetup);
                $meetup->getLibrary()->removeMeetup($meetup);
                $meetupRepository->remove($meetup, true);
                
            }else if ($meetup->getPerson2() === $this) {
                $meetup->getPerson1()->removeMeetupLocally($meetup);
                $meetup->getLibrary()->removeMeetup($meetup);
                $meetupRepository->remove($meetup, true);
            }
        }

        return $this;
    }

    /**
     * @return Collection<Book>
     */
    public function getFavoriteBooks(): Collection
    {
        return $this->favoriteBooks;
    }

    public function addFavoriteBook(Book $book): self
    {
        if (!$this->favoriteBooks->contains($book)) {
            $this->favoriteBooks[] = $book;
            $book->addUser($this);
        }

        return $this;
    }

    public function removeFavoriteBook(Book $book): self
    {
        if ($this->favoriteBooks->removeElement($book)) {
            $book->removeUser($this);
        }

        return $this;
    }

    public function getProfilepicFilename(): ?string
    {
        return $this->profilepicFilename;
    }

    public function setProfilepicFilename(?string $profilepicFilename): self
    {
        $this->profilepicFilename = $profilepicFilename;

        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $profilepicFile
     */
    public function setProfilepicFile(?File $profilepicFile = null): void
    {
        $this->profilepicFile = $profilepicFile;

        if (null !== $profilepicFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getProfilepicFile(): ?File
    {
        return $this->profilepicFile;
    }

    public function getProfilepicSize(): ?int
    {
        return $this->profilepicSize;
    }

    public function setProfilepicSize(?int $profilepicSize): self
    {
        $this->profilepicSize = $profilepicSize;

        return $this;
    }

}
