<?php

namespace App\Tests\application\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PageRouteTest extends WebTestCase
{
    public function testRouteHome(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/home');
        $userRepository = static::getContainer()->get(UserRepository::class);
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->userRepository = $this->entityManager->getRepository(User::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('aocheng.zhao@student.kuleuven.be');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('#subtitle','Meet book lovers');
        $this->assertSelectorTextContains('#catchphrase', 'Find Books and Book Lovers!!');
    }

    public function testRouteLogin(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('form >main>section>div>label', 'Email');
    }

    public function testRouteRegister(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('form', 'Email');
        $form = $crawler->selectButton('Register')->form();
    }

//    public function testRouteBook(): void
//    {
//        $client = static::createClient();
//        $crawler = $client->request('GET', '/book');
//
//        $this->assertResponseIsSuccessful();
//        $this->assertSelectorTextContains('main>h1', 'All Books');
//    }



}
