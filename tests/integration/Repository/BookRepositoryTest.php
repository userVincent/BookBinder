<?php

namespace App\Tests\unit\Repository;

use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;



class BookRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;
    private $book;
    private $bookRepository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->bookRepository = $this->entityManager->getRepository(Book::class);
        $this->book = new Book();
        $this->book->setTitle('testBook');
        $this->book->setISBN('6666666666666');//13 digits
//        $this->book->setAuthor('tester');
//        $this->book->setPages('999');
//        $this->book->setRating('9.9');

    }

    public function testSave()
    {
        $this->bookRepository->save($this->book, true);

        // Retrieve the saved user from the database
        $savedBook = $this->bookRepository->find($this->book->getId());

        $this->assertEquals($this->book->getISBN(), $savedBook->getISBN());
//        $this->assertEquals($this->book->getAuthor(), $savedBook->getAuthor());
        $this->assertEquals($this->book->getLibraries(), $savedBook->getLibraries());
//        $this->assertEquals($this->book->getRating(), $savedBook->getRating());
        $this->assertEquals($this->book->getTitle(), $savedBook->getTitle());
    }

    public function testRemove()
    {
        $this->bookRepository->save($this->book, true);
        $bookId = $this->book->getId();//first retrieve the userId before removing
        $this->bookRepository->remove($this->book, true);
        $removedBook = $this->bookRepository->find($bookId);
        $this->assertNull($removedBook);
    }

    public function testSearchBooks()
    {
        $this->bookRepository->save($this->book, true);
        $query = 'testBook';
        $result = $this->bookRepository->searchBooks($query);

        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}