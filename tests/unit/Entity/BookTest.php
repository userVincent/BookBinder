<?php

namespace App\Tests\unit\Entity;

use App\Entity\Book;
use App\Entity\Meetup;
use DateTime;
use App\Entity\User;
use App\Entity\Library;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class BookTest extends TestCase
{
    private $library;
    private $book;



    protected function setUp(): void
    {
        $this->book = new Book();
        $this->library = new Library();
        $this->assertNotNull($this->book);
        $this->assertNotNull($this->book->getLibraries());

        $this->book->setTitle('testBook');
        $this->book->setISBN('6666666666666');//13 digits
        $this->book->setAuthor('tester');
        $this->book->setPages('999');
        $this->book->setRating('9.9');
        $this->book->setLanguage('en');
        $this->book->setPages(100);
        $this->book->addLibrary($this->library);

        //
        $this->library->setName('testLib');
        $this->library->setLatitude('0.00');
        $this->library->setLongitude('1.00');



    }

    public function testGettersAndSetters()
    {
        $this->assertNull($this->book->getId());
        $this->assertEquals('testBook', $this->book->getTitle());
        $this->assertEquals('9.9', $this->book->getRating());
        $this->assertEquals('6666666666666', $this->book->getISBN());
        $this->assertEquals('tester', $this->book->getAuthor());
        $this->assertEquals('en', $this->book->getLanguage());
        $this->assertEquals(100, $this->book->getPages());
        $this->assertContains($this->library, $this->book->getLibraries());


        //test remove
        //removeLibrary
        $this->book->removeLibrary($this->library);
        $this->assertNotContains($this->library, $this->book->getLibraries());


    }
}