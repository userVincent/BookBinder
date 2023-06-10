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
    private $user;



    protected function setUp(): void
    {
        $this->book = new Book();
        $this->library = new Library();
        $this->user = new User();
        $this->assertNotNull($this->book);
        $this->assertNotNull($this->book->getLibraries());

        $this->book->setTitle('testBook');
        $this->book->setISBN('6666666666666');//13 digits
        $this->book->addUser($this->user);
        $this->user->setFirstName('Aocheng');
        $this->user->setLastName('Zhao');
        $this->user->setEmail('aocheng.zhao@test.com');
        $this->user->setAddress('Leuven');
        $this->birthday = new DateTime('1990-01-01'); // Replace with the desired birthday date
        $this->user->setBirthday($this->birthday);
        $this->user->setPassword('Abc123456');
        $this->book->addUser($this->user);
//        $this->book->setAuthor('tester');
//        $this->book->setPages('999');
//        $this->book->setRating('9.9');
//        $this->book->setLanguage('en');
//        $this->book->setPages(100);
        $this->book->addLibrary($this->library);

        //
        $this->library->setName('testLib');
        $this->library->setLatitude('0.00');
        $this->library->setLongitude('1.00');

//        $this->assertEquals('9.9', $this->book->getRating());

//        $this->assertEquals('tester', $this->book->getAuthor());
//        $this->assertEquals('en', $this->book->getLanguage());
//        $this->assertEquals(100, $this->book->getPages());


    }

    public function testGetId()
    {
        $this->assertNull($this->book->getId());
    }
    public function testTitleGettersAndSetters()
    {
        $this->assertEquals('testBook', $this->book->getTitle());
    }
    public function testISBNGettersAndSetters()
    {
        $this->assertEquals('6666666666666', $this->book->getISBN());
    }
    public function testAddGetLibraries()
    {
        $this->assertContains($this->library, $this->book->getLibraries());
    }

    public function testRemoveLibrary()
    {
        //removeLibrary
        $this->book->removeLibrary($this->library);
        $this->assertNotContains($this->library, $this->book->getLibraries());
    }

    public function testAddGetUsers()
    {
        $this->assertContains($this->user, $this->book->getUsers());
    }
}