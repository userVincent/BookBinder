<?php

namespace App\Tests\application\Controller;

use App\Repository\LibraryRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProfileControllerTest extends WebTestCase
{
    private $client;
    private $user;
    private $user2;
    private $userRepository;
    private $libraryRepository;
    private $crawler;

    protected function setUp(): void
    {
        parent::setUp();

        if (!$this->client) {
            $this->client = static::createClient();
            $this->client->catchExceptions(false);
        }
        $this->libraryRepository = static::getContainer()->get(LibraryRepository::class);
        $this->userRepository = static::getContainer()->get(UserRepository::class);
        // retrieve the test user
        $this->user2 = $this->userRepository->findOneByEmail('tester2@gmail.com');
        $this->user = $this->userRepository->findOneByEmail('tester1@gmail.com');
        // simulate $user1 being logged in
        $this->client->loginUser($this->user);

        $this->crawler = $this->client->request('GET', '/home');

    }

    public function testIndex()
    {
        $this->crawler=$this->client->request('GET', '/profiles');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('main > div > h1', 'User Profiles');
        $this->assertCount(1, $this->crawler->filter('table.table')); // Ensure the table exists

        $tableRows = $this->crawler->filter('table.table tbody tr');
        $this->assertGreaterThan(0, $tableRows->count()); // Ensure there are rows in the table

        // Loop through each row and assert the values of the user data
        $tableRows->each(function ($row) {
            $userId = $row->filter('td')->eq(0)->text();
            $firstName = $row->filter('td')->eq(1)->text();
            $lastName = $row->filter('td')->eq(2)->text();
            $email = $row->filter('td')->eq(3)->text();

            // Perform assertions on the user data
            $this->assertNotEmpty($userId);
            $this->assertNotEmpty($firstName);
            $this->assertNotEmpty($lastName);
            $this->assertNotEmpty($email);
        });
        $userTable1 = $tableRows->eq(0);
        $userId1 = $userTable1->filter('td')->eq(0)->text();
        $firstName1 = $userTable1->filter('td')->eq(1)->text();
        $lastName1 = $userTable1->filter('td')->eq(2)->text();
        $email1 = $userTable1->filter('td')->eq(3)->text();

        $userTable2 = $tableRows->eq(1);
        $userId2 = $userTable2->filter('td')->eq(0)->text();
        $firstName2 = $userTable2->filter('td')->eq(1)->text();
        $lastName2 = $userTable2->filter('td')->eq(2)->text();
        $email2 = $userTable2->filter('td')->eq(3)->text();


        $this->assertEquals($this->user->getId(),$userId1);
        $this->assertNotEmpty($this->user->getFirstName(),$firstName1);
        $this->assertNotEmpty($this->user->getLastName(),$lastName1);
        $this->assertNotEmpty($this->user->getEmail(),$email1);

        $this->assertEquals($this->user2->getId(),$userId2);
        $this->assertNotEmpty($this->user2->getFirstName(),$firstName2);
        $this->assertNotEmpty($this->user2->getLastName(),$lastName2);
        $this->assertNotEmpty($this->user2->getEmail(),$email2);

    }
    public function testShow()
    {
        $this->client->request('GET', '/profile/' . $this->user->getId());
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('main > div > h1',$this->user->getFirstName() );
    }
}