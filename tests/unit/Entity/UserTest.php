<?php

namespace App\Tests\unit\Entity;

use App\Entity\Library;
use App\Entity\User;
use App\Entity\Meetup;
use PHPUnit\Framework\TestCase;
use DateTime;

class UserTest extends TestCase
{
    public function testGettersAndSetters()
    {
        // Create a new instance of the User class
        $user = new User();
        $library = new Library();
        $library->setName('testLibrary');
        // Set test values using the setter methods
        $user->setFirstName('Aocheng');
        $user->setLastName('Zhao');
        $user->setEmail('aocheng.zhao@test.com');
        $user->setAddress('Leuven');
        $birthday = new DateTime('1990-01-01'); // Replace with the desired birthday date
        $user->setBirthday($birthday);
        $user->setPassword('Abc123456');
        $roles = ['ROLE_ADMIN', 'ROLE_USER'];
        $user->setRoles($roles);
        $user->addLibrary($library);
        //meetup settings
        $person2 = new User(); // Assuming you have a User instance for person2
        $date = new DateTime();
        $time = new DateTime();
        $meetup = new Meetup();
        $user->addMeetup($meetup, $person2, $library, $date, $time);



        // Use the getter methods to retrieve the values
        $firstName = $user->getFirstName();
        $lastName = $user->getLastName();
        $email = $user->getEmail();
        $address = $user->getAddress();
        $gotBirthday = $user->getBirthday();
        $password = $user->getPassword();
        $gotRoles = $user->getRoles();
        $gotLibraries = $user->getLibraries();
        $meetups = $user->getMeetups();
        // Call the addMeetup() method




        // Assert that the retrieved values match the set values
        $this->assertEquals('Aocheng', $firstName);
        $this->assertEquals('Zhao', $lastName);
        $this->assertEquals('aocheng.zhao@test.com', $email);
        $this->assertEquals('Leuven', $address);
        $this->assertEquals($birthday, $gotBirthday);
        $this->assertEquals('Abc123456', $password);
        $this->assertEquals($roles, $gotRoles);
        //many to many
        $this->assertContains($library,$gotLibraries);
        $this->assertContains($user,$library->getMembers());

        $this->assertContains($meetup,$meetups);

        $this->assertEquals($library, $meetup->getLibrary());
        $this->assertEquals($user,$meetup->getPerson1());
        $this->assertEquals($person2,$meetup->getPerson2());
        $this->assertEquals($date,$meetup->getDate());
        $this->assertEquals($time,$meetup->getTime());

        //test remove lib
        $gotLibraries = $user->removeLibrary($library)->getLibraries();
        $this->assertNotContains($library,$gotLibraries);// library removed from user
        $this->assertNotContains($user,$library->getMembers());// user removed from the library

    }
}