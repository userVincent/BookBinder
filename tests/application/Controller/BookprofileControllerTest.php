<?php

namespace App\Tests\application\Controller;

use App\Repository\LibraryRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookprofileControllerTest extends WebTestCase
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
        $this->user = $this->userRepository->findOneByEmail('tester1@gmail.com');
        // simulate $user1 being logged in
        $this->client->loginUser($this->user);

        $this->crawler = $this->client->request('GET', '/home');


    }

    public function testSearchBooks()
    {
        $this->client->request('POST', '/favorite-book/123/book-title');
        $this->client->request('POST', '/favorite-book/1234/book-title2');
        $this->client->request('GET', '/search-books?query=book-title');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('main > h1', 'All Books');
        $this->assertSelectorTextContains('main >ul >li', 'book-title');
    }

    public function testBookProfile()
    {
        $this->crawler = $this->client->request('GET', '/book_profile/testtitle/123');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('#isbn', '123');
    }


}