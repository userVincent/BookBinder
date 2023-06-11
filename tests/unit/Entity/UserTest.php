<?php

namespace App\Tests\unit\Entity;

use App\Entity\Book;
use App\Entity\Library;
use App\Entity\User;
use App\Entity\Meetup;
use App\Repository\MeetupRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\File\File;

class UserTest extends KernelTestCase
{
    private $user;
    private $library;
    private $book;
    private $birthday;
    private $roles;
    private $person2;
    private $meetup;
    private $date;
    private $time;
    private $file;
    private $filePath;
    protected function setUp(): void
    {
        parent::setUp();

        // Create a new instance of the User class
        $this->user = new User();
        $this->library = new Library();
        $this->library->setName('testLibrary');
        $this->book = new Book();
        $this->book->setTitle('testBook');
        $this->book->setISBN('6666666666666');//13 digits
        // Set test values using the setter methods
        $this->user->setFirstName('Aocheng');
        $this->user->setLastName('Zhao');
        $this->user->setEmail('aocheng.zhao@test.com');
        $this->user->setAddress('Leuven');
        $this->birthday = new DateTime('1990-01-01'); // Replace with the desired birthday date
        $this->user->setBirthday($this->birthday);
        $this->user->setPassword('Abc123456');
        $this->user->setAbout('This is about');
        $this->user->setInterests('testing');
        $this->user->setAge(10);
        ///
        $this->filePath = 'public/uploads/profilepics/young-man-avatar-character-vector-14213210-6475001bcba56016513834.jpg';

        $this->file = new File($this->filePath);
        $this->user->setProfilepicFile($this->file);
        $this->user->setProfilepicFilename('young-man-avatar-character-vector-14213210-6475001bcba56016513834.jpg');
        $this->user->setProfilepicSize(10000);
        ///
        $this->roles = ['ROLE_ADMIN', 'ROLE_USER'];
        $this->user->setRoles($this->roles);
        $this->user->addLibrary($this->library);
        //meetup settings
        $this->person2 = new User(); // Assuming you have a User instance for person2
        $this->date = new DateTime();
        $this->time = new DateTime();
        $this->meetup = new Meetup();
        $this->user->addMeetup($this->meetup, $this->person2, $this->library, $this->date, $this->time);


    }
    public function testFirstNameGettersAndSetters()
    {
        $firstName = $this->user->getFirstName();
        $this->assertEquals('Aocheng', $firstName);
    }
    public function testLastNameGettersAndSetters()
    {
        $lastName = $this->user->getLastName();
        $this->assertEquals('Zhao', $lastName);
    }
    public function testEmailGettersAndSetters()
    {
        $email = $this->user->getEmail();
        $this->assertEquals('aocheng.zhao@test.com', $email);
    }
    public function testAddressGettersAndSetters()
    {
        $address = $this->user->getAddress();
        $this->assertEquals('Leuven', $address);
    }
    public function testBirthdayGettersAndSetters()
    {
        $gotBirthday = $this->user->getBirthday();
        $this->assertEquals($this->birthday, $gotBirthday);
    }
    public function testPasswordGettersAndSetters()
    {
        $password = $this->user->getPassword();
        $this->assertEquals('Abc123456', $password);
    }
    public function testAboutGettersAndSetters()
    {
        $about = $this->user->getAbout();
        $this->assertEquals('This is about', $about);
    }
    public function testInterestsGettersAndSetters()
    {
        $interest = $this->user->getInterests();
        $this->assertEquals('testing', $interest);
    }
    public function testAgeGettersAndSetters()
    {
        $age = $this->user->getAge();
        $this->assertEquals(10, $age);
    }
    public function testProfilepicFileGettersAndSetters()
    {
        $profilepic = $this->user->getProfilepicFile();
        $this->assertEquals($this->file, $profilepic);
    }
    public function testProfilepicFilenameGettersAndSetters()
    {
        $fileName = $this->user->getProfilepicFilename();
        $this->assertEquals('young-man-avatar-character-vector-14213210-6475001bcba56016513834.jpg', $fileName);
    }
    public function testProfilepicSizeGettersAndSetters()
    {
        $fileSize = $this->user->getProfilepicSize();
        $this->assertEquals(10000, $fileSize);
    }
    public function testRolesGettersAndSetters()
    {
        $gotRoles = $this->user->getRoles();
        $this->assertEquals($this->roles, $gotRoles);
    }
    public function testLibrariesGettersAndSetters()
    {
        $gotLibraries = $this->user->getLibraries();
        $this->assertContains($this->library,$gotLibraries);
        $this->assertContains($this->user,$this->library->getMembers());
        //
        $this->assertEquals($this->library, $this->meetup->getLibrary());
        $this->assertEquals($this->user,$this->meetup->getPerson1());
        $this->assertEquals($this->person2,$this->meetup->getPerson2());
        $this->assertEquals($this->date,$this->meetup->getDate());
        $this->assertEquals($this->time,$this->meetup->getTime());
    }
    public function testRemoveLibrary()
    {
        //test remove lib
        $gotLibraries = $this->user->removeLibrary($this->library)->getLibraries();
        $this->assertNotContains($this->library,$gotLibraries);// library removed from user
        $this->assertNotContains($this->user,$this->library->getMembers());// user removed from the library

    }
    public function testMeetupsGettersAndSetters()
    {
        $meetups = $this->user->getMeetups();
        $this->assertContains($this->meetup,$meetups);
        //
        $this->assertEquals($this->library, $this->meetup->getLibrary());
        $this->assertEquals($this->user,$this->meetup->getPerson1());
        $this->assertEquals($this->person2,$this->meetup->getPerson2());
        $this->assertEquals($this->date,$this->meetup->getDate());
        $this->assertEquals($this->time,$this->meetup->getTime());

    }
    public function testRemoveMeetup()
    {
        $kernel = self::bootKernel();

        $entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $meetupRepository = $entityManager->getRepository(Meetup::class);

        $gotMeetup = $this->user->removeMeetup($this->meetup,$meetupRepository)->getMeetups();
        $this->assertNotContains($this->meetup,$gotMeetup);
    }
    public function testRemoveMeetup2()
    {
        $kernel = self::bootKernel();

        $entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $meetupRepository = $entityManager->getRepository(Meetup::class);
        $meetup2 = new Meetup();
        $library2 = new Library();
        $library2->setName('testLibrary2');
        $date = new DateTime();
        $time = new DateTime();
        $this->user->addMeetup($meetup2, $this->person2, $library2, $date, $time);
        $meetup2->setPerson1($this->person2);
        $meetup2->setPerson2($this->user);

        $this->assertEquals($this->user->getId(),$meetup2->getPerson2()->getId());
        $gotMeetup2 = $this->user->removeMeetup($meetup2,$meetupRepository)->getMeetups();
        $this->assertNotContains($meetup2,$gotMeetup2);
    }
    public function testRemoveMeetupLocally()
    {
        $gotMeetup = $this->user->removeMeetupLocally($this->meetup)->getMeetups();
        $this->assertNotContains($this->meetup,$gotMeetup);

    }
    public function testRemoveMeetupLocally2()
    {
        $meetup2 = new Meetup();
        $library2 = new Library();
        $library2->setName('testLibrary2');
        $date = new DateTime();
        $time = new DateTime();
        $this->user->addMeetup($meetup2, $this->person2, $library2, $date, $time);
        $meetup2->setPerson1($this->person2);
        $meetup2->setPerson2($this->user);

        $this->assertEquals($this->user->getId(),$meetup2->getPerson2()->getId());
        $gotMeetup2 = $this->user->removeMeetupLocally($meetup2)->getMeetups();
        $this->assertNotContains($meetup2,$gotMeetup2);
    }

//meetup not finished
    public function testFavoriteBooksGettersAndSetters()
    {
        $this->user->addFavoriteBook($this->book);
        $gotBooks = $this->user->getFavoriteBooks();
        $this->assertContains($this->book,$gotBooks);
    }
    public function testRemoveFavoriteBook()
    {
        $this->user->addFavoriteBook($this->book);
        $gotBooks = $this->user->removeFavoriteBook($this->book)->getFavoriteBooks();
        $this->assertNotContains($this->book,$gotBooks);
    }
    public function testGetUpdatedAt(): void
    {
        $user = new User();
        $expectedDateTime = new \DateTimeImmutable('2022-01-01 00:00:00');

        $reflection = new \ReflectionClass(User::class);
        $updatedAtProperty = $reflection->getProperty('updatedAt');
        $updatedAtProperty->setAccessible(true);
        $updatedAtProperty->setValue($user, $expectedDateTime);

        $actualDateTime = $user->getUpdatedAt();

        $this->assertInstanceOf(\DateTimeInterface::class, $actualDateTime);
        $this->assertEquals($expectedDateTime, $actualDateTime);
    }
}