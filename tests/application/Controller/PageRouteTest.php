<?php

namespace App\Tests\application\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PageRouteTest extends WebTestCase
{

    private $client;

    protected function setUp(): void
    {
        parent::setUp();

        if (!$this->client) {
            $this->client = static::createClient();
        }

    }


    public function testNoneAuthenticationRoute():void
    {
        $crawler = $this->client->request('GET', '/home');
        $this->assertTrue($this->client->getResponse()->isRedirection());
        // Check if the redirect target is the login page
        $this->assertStringContainsString('/login', $this->client->getResponse()->headers->get('Location'));
//        $crawler = $client->request('GET', '/profiles');
//        $this->assertTrue($client->getResponse()->isRedirection());
//        // Check if the redirect target is the login page
//        $this->assertStringContainsString('/login', $client->getResponse()->headers->get('Location'));
//        $crawler = $client->request('GET', '/search-books');
//        $this->assertTrue($client->getResponse()->isRedirection());
//        // Check if the redirect target is the login page
//        $this->assertStringContainsString('/login', $client->getResponse()->headers->get('Location'));
//        $crawler = $client->request('GET', '/meetups');
//        $this->assertTrue($client->getResponse()->isRedirection());
//        // Check if the redirect target is the login page
//        $this->assertStringContainsString('/login', $client->getResponse()->headers->get('Location'));
//        $crawler = $client->request('GET', '/meetups/arrange');
//        $this->assertTrue($client->getResponse()->isRedirection());
//        // Check if the redirect target is the login page
//        $this->assertStringContainsString('/login', $client->getResponse()->headers->get('Location'));
//        $crawler = $client->request('GET', '/meetups/arrange/library_select');
//        $this->assertTrue($client->getResponse()->isRedirection());
//        // Check if the redirect target is the login page
//        $this->assertStringContainsString('/login', $client->getResponse()->headers->get('Location'));
//        $crawler = $client->request('GET', '/meetups/arrange/person_select');
//        $this->assertTrue($client->getResponse()->isRedirection());
//        // Check if the redirect target is the login page
//        $this->assertStringContainsString('/login', $client->getResponse()->headers->get('Location'));
    }

    /**
     * @depends testNoneAuthenticationRoute
     */
    public function testAuthenticatedRouteHome(): void
    {
        $this->loginTestUser($this->client);
        $crawler = $this->client->request('GET', '/home');
        $this->assertSelectorTextContains('#subtitle','Meet book lovers');
        $this->assertSelectorTextContains('#catchphrase', 'Find Books and Book Lovers!!');
    }

    /**
     * @depends testNoneAuthenticationRoute
     */
    public function testAuthenticatedRouteLibraries(): void
    {
        $this->loginTestUser($this->client);
        $crawler = $this->client->request('GET', '/libraries');
        $this->assertSelectorTextContains('#subtitle','Meet book lovers');
        $this->assertSelectorTextContains('main > h1', 'Libraries');
    }
    /**
     * @depends testNoneAuthenticationRoute
     */
    public function testAuthenticatedRouteMeetups(): void
    {
        $this->loginTestUser($this->client);
        $crawler = $this->client->request('GET', '/meetups');
        $this->assertSelectorTextContains('#subtitle','Meet book lovers');
        $this->assertSelectorTextContains('main > h1', 'Your Meetups');
    }
    /**
     * @depends testNoneAuthenticationRoute
     */
    public function testAuthenticatedRouteProfile(): void
    {
        $testUser = $this->loginTestUser($this->client);

        $crawler = $this->client->request('GET', '/profile');
        $this->assertSelectorTextContains('#subtitle','Meet book lovers');
        $this->assertSelectorTextContains('.user-name', $testUser->getFirstName());
        $this->assertSelectorTextContains('.user-name', $testUser->getLastName());
    }


    public function testRouteLogin(): void
    {
        $crawler = $this->client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('form >main>section>div>label', 'Email');
    }


    public function testRouteRegister(): void
    {
        $crawler = $this->client->request('GET', '/register');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('form', 'Email');
        $form = $crawler->selectButton('Register')->form();
    }


    protected function loginTestUser($client):User
    {
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Sign in')->form();
        $form->setValues([
            '_username' => 'tester1@gmail.com',
            '_password' => '123456',
        ]);

        $client->submit($form);
        // Access the user through the container
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);
        $userRepository = $entityManager->getRepository(User::class);

        // Retrieve the test user
        $testUser = $userRepository->findOneByEmail('tester1@gmail.com');
        $this->assertNotNull($testUser, 'Test user is null');

        return $testUser;
    }


}
