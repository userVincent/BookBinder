<?php

namespace App\Tests\unit\Repository;

use App\Entity\Book;
use App\Entity\Library;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class LibraryRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;
    private $library;
    private $libraryRepository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->libraryRepository = $this->entityManager->getRepository(Library::class);
        $this->library = new Library();
        $this->library->setName('testLibrary');
        $this->library->setLatitude('0.00');
        $this->library->setLongitude('1.00');


    }

    public function testSave()
    {
        $this->libraryRepository->save($this->library, true);

        // Retrieve the saved user from the database
        $savedLibrary = $this->libraryRepository->find($this->library->getId());

        $this->assertEquals($this->library->getName(), $savedLibrary->getName());
        $this->assertEquals($this->library->getLongitude(), $savedLibrary->getLongitude());
        $this->assertEquals($this->library->getLatitude(), $savedLibrary->getLatitude());
        $this->assertEquals($this->library->getBooks(), $savedLibrary->getBooks());
        $this->assertEquals($this->library->getMembers(), $savedLibrary->getMembers());
        $this->assertEquals($this->library->getMeetups(), $savedLibrary->getMeetups());
        $this->assertEquals($this->library->getId(), $savedLibrary->getId());


    }

    public function testRemove()
    {
        $this->libraryRepository->save($this->library, true);
        $libraryId = $this->library->getId();//first retrieve the userId before removing
        $this->libraryRepository->remove($this->library, true);
        $removedLibrary = $this->libraryRepository->find($libraryId);
        $this->assertNull($removedLibrary);
    }


    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}