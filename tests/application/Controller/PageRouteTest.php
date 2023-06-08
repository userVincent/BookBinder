<?php

namespace App\Tests\application\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PageRouteTest extends WebTestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        if (!self::$client) {
            self::$client = static::createClient();
        }

        // Perform additional setup actions if needed
        if (!self::$additionalSetupPerformed) {
            $this->performAdditionalSetup();
            self::$additionalSetupPerformed = true;
        }
    }

    protected function performAdditionalSetup(): void
    {
        // Perform additional setup actions here
    }
    public function testNoneAuthenticationRoute():void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/home');
        $this->assertTrue($client->getResponse()->isRedirection());
        // Check if the redirect target is the login page
        $this->assertStringContainsString('/login', $client->getResponse()->headers->get('Location'));
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

    public function testRouteHome(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/home');
        $this->assertTrue($client->getResponse()->isRedirection());
        // Check if the redirect target is the login page
        $this->assertStringContainsString('/login', $client->getResponse()->headers->get('Location'));

        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $userRepository = $entityManager->getRepository(User::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('aocheng.zhao@student.kuleuven.be');
        $this->assertNotNull($testUser, 'Test user is null');
        // simulate $testUser being logged in
        $client->loginUser($testUser);
        $crawler = $client->followRedirect();
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
    private function loginTestUser():void
    {
        $form = $crawler->selectButton('Sign in')->form();
        $form->setValues([
            '_username' => 'aocheng.zhao@student.kuleuven.be',
            '_password' => '123456',
        ]);

        $this->client->submit($form);
    }


}
