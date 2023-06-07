<?php

namespace App\Tests\unit\Repository;

use App\Entity\Book;
use App\Entity\Library;
use App\Entity\User;
use App\Repository\LibraryRepository;
use App\Repository\UserRepository;
use DateTime;
use App\Entity\Meetup;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class MeetupRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;
    private $meetup;
    private $meetupRepository;
    private $userRepository;
    private $libraryRepository;
    private $user1;
    private $user2;
    private $library;
    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->meetupRepository = $this->entityManager->getRepository(Meetup::class);
        $this->meetup = new Meetup();
        $this->user1 = new User();
        $this->user2 = new User();
        $this->library = new Library();
        ///////
        $this->library->setName('testLib');
        $this->library->setLatitude('0.00');
        $this->library->setLongitude('1.00');
        $this->user1->setEmail('12345@gmail.com');
        $this->user1->setPassword('Abc123456');
        $this->user1->setLastName('me');
        $this->user1->setFirstName('tester');
        $birthday = new DateTime('1990-01-01'); // Replace with the desired birthday date
        $this->user1->setBirthday($birthday);
        $this->user1->setAddress('my address');
        $this->user2->setEmail('54321@gmail.com');
        $this->user2->setPassword('Abc123456');
        $this->user2->setLastName('me');
        $this->user2->setFirstName('tester');
        $this->user2->setBirthday($birthday);
        $this->user2->setAddress('my address');
        ///////
        $this->userRepository = $this->entityManager->getRepository(User::class);
        $this->libraryRepository = $this->entityManager->getRepository(Library::class);
        $this->libraryRepository->save($this->library,true);
        $this->userRepository->save($this->user1,true);
        $this->userRepository->save($this->user2,true);

        $this->meetup->setDate(new DateTime('1990-01-01'));
        $this->meetup->setTime(new DateTime('20:10:00'));
        $this->meetup->setPerson1($this->user1);
        $this->meetup->setPerson2($this->user2);
        $this->meetup->setLibrary($this->library);


    }

    public function testSave()
    {
        $this->meetupRepository->save($this->meetup, true);

        // Retrieve the saved user from the database
        $savedLibrary = $this->meetupRepository->find($this->meetup->getId());

        $this->assertEquals($this->meetup->getTime(), $savedLibrary->getTime());
        $this->assertEquals($this->meetup->getDate(), $savedLibrary->getDate());
        $this->assertEquals($this->meetup->getId(), $savedLibrary->getId());
        $this->assertEquals($this->meetup->getPerson1(), $savedLibrary->getPerson1());
        $this->assertEquals($this->meetup->getPerson2(), $savedLibrary->getPerson2());
        $this->assertEquals($this->meetup->getLibrary(), $savedLibrary->getLibrary());


    }

    public function testRemove()
    {
        $this->meetupRepository->save($this->meetup, true);
        $meetupId = $this->meetup->getId();//first retrieve the userId before removing
        $this->meetupRepository->remove($this->meetup, true);
        $removedMeetup = $this->meetupRepository->find($meetupId);
        $this->assertNull($removedMeetup);
    }


    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}