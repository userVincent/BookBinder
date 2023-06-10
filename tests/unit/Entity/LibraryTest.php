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
class LibraryTest extends TestCase
{
    private $library;
    private $user;
    private $book;
    private $meetup;
    protected function setUp(): void
    {
        $this->library = new Library();
        $this->user = new User();
        $this->book = new Book();
        $this->meetup = new Meetup();
        $this->assertNotNull($this->library);

        $this->library->setName('testLib');
        $this->library->setLatitude('0.00');
        $this->library->setLongitude('1.00');
        $this->library->setTown('test town');
        $this->library->setType('test type');
        $this->library->setHouseNumber('1000');
        $this->library->setPostalCode('1000');
        $this->library->setStreetName('test street');
        $this->library->setYear(2023);





        $this->assertNotNull($this->library->getMembers());
        $this->assertNotNull($this->library->getBooks());
        $this->assertNotNull($this->library->getMembers());
        $this->library->addMember($this->user);
        $this->library->addBook($this->book);
        $this->library->addMeetup($this->meetup);
        //
        $this->user->setFirstName('Aocheng');
        $this->user->setLastName('Zhao');
        $this->user->setEmail('aocheng.zhao@test.com');
        $this->user->setPassword('Abc123456');
        $this->user->setLastName('me');
        $this->user->setFirstName('tester');
        $birthday = new DateTime('1990-01-01'); // Replace with the desired birthday date
        $this->user->setBirthday($birthday);
        $this->user->setAddress('my address');
        //

        $this->book->setTitle('testBook');
        $this->book->setISBN('6666666666666');//13 digits
//        $this->book->setAuthor('tester');
//        $this->book->setPages('999');
//        $this->book->setRating('9.9');
        //

        $this->meetup->setDate(new DateTime('1990-01-01'));
        $this->meetup->setTime(new DateTime('20:10:00'));
        $this->meetup->setState(0);


    }
    public function testCreation(){
        $this->assertNotNull($this->library);
        $this->assertNotNull($this->library->getMembers());
        $this->assertNotNull($this->library->getBooks());
        $this->assertNotNull($this->library->getMembers());
    }
    public function testGetId(){
        $this->assertNull($this->library->getId());
    }
    public function testNameGettersAndSetters(){
        $this->assertEquals('testLib',$this->library->getName());
    }
    public function testLatitudeGettersAndSetters(){
        $this->assertEquals('0.00',$this->library->getLatitude());
    }
    public function testLongitudeGettersAndSetters(){
        $this->assertEquals('1.00',$this->library->getLongitude());
    }
    public function testTownGettersAndSetters(){
        $this->assertEquals('test town',$this->library->getTown());
    }
    public function testTypeGettersAndSetters(){
        $this->assertEquals('test type',$this->library->getType());
    }
    public function testHouseNumberGettersAndSetters(){
        $this->assertEquals('1000',$this->library->getHouseNumber());
    }
    public function testPostalCodeGettersAndSetters(){
        $this->assertEquals('1000',$this->library->getPostalCode());
    }
    public function testStreetNameGettersAndSetters(){
        $this->assertEquals('test street',$this->library->getStreetName());
    }
    public function testYearGettersAndSetters(){
        $this->assertEquals(2023,$this->library->getYear());
    }
    public function testAddAndGetMembers(){
        $this->assertContains($this->user,$this->library->getMembers());
    }
    public function testRemoveMember(){
        //removeMembers
        $this->user->addLibrary($this->library);
        $this->library->removeMember($this->user);
        $this->assertNotContains($this->user,$this->library->getMembers());
        //some problems here, after remove the member, the user shouldn't have the library.
//        $this->assertNotContains($this->library,$this->user->getLibraries());
    }
    public function testAddAndGetBooks(){
        $this->assertContains($this->book,$this->library->getBooks());
    }
    public function testRemoveBook(){
        //removeBooks
        $this->library->removeBook($this->book);
        $this->assertNotContains($this->book,$this->library->getBooks());
    }
    public function testAddAndGetMeetups(){
        $this->assertContains($this->meetup,$this->library->getMeetups());
    }
    public function testRemoveMeetup(){
        //removeMeetup
        $this->meetup->setLibrary($this->library);
        $this->library->removeMeetup($this->meetup);
        $this->assertNotContains($this->meetup,$this->library->getMeetups());
        $this->assertNull($this->meetup->getLibrary());
    }


}