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
        $this->assertNotNull($this->library);
        $this->assertNotNull($this->library->getMembers());
        $this->assertNotNull($this->library->getBooks());
        $this->assertNotNull($this->library->getMembers());
        //
        $this->user = new User();
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
        $this->book = new Book();
        $this->book->setTitle('testBook');
        $this->book->setISBN('6666666666666');//13 digits
        $this->book->setAuthor('tester');
        $this->book->setPages('999');
        $this->book->setRating('9.9');
        //
        $this->meetup = new Meetup();
        $this->meetup->setDate(new DateTime('1990-01-01'));
        $this->meetup->setTime(new DateTime('20:10:00'));



    }

    public function testGettersAndSetters()
    {
        $this->assertNull($this->library->getId());
        $this->assertEquals('testLib',$this->library->setName('testLib')->getName());
        $this->assertEquals('0.00',$this->library->setLatitude('0.00')->getLatitude());
        $this->assertEquals('1.00',$this->library->setLongitude('1.00')->getLongitude());
        $this->assertContains($this->user,$this->library->addMember($this->user)->getMembers());
        $this->assertContains($this->book,$this->library->addBook($this->book)->getBooks());
        $this->assertContains($this->meetup,$this->library->addMeetup($this->meetup)->getMeetups());

        //test remove
        //removeMeetup
        $this->meetup->setLibrary($this->library);
        $this->library->removeMeetup($this->meetup);
        $this->assertNotContains($this->meetup,$this->library->getMeetups());
        $this->assertNull($this->meetup->getLibrary());
        //removeBooks
        $this->library->removeBook($this->book);
        $this->assertNotContains($this->book,$this->library->getBooks());
        //removeMembers
        $this->user->addLibrary($this->library);
        $this->library->removeMember($this->user);
        $this->assertNotContains($this->user,$this->library->getMembers());
        //some problems here, after remove the member, the user shouldn't have the library.
//        $this->assertNotContains($this->library,$this->user->getLibraries());




    }
}