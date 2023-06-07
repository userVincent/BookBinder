<?php

namespace App\Tests\application\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{
    private $client;
    private $crawler;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->crawler = $this->client->request('GET', '/login');
    }

    public function testResponse(): void
    {
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Login to BookBinder');
    }

    public function testLogin(): void
    {
        $form = $this->crawler->selectButton('Sign in')->form();
        $form->setValues([
            '_username' => 'aocheng.zhao@student.kuleuven.be',
            '_password' => '123456',
        ]);



        $this->client->submit($form);
        // Follow the redirection
        $this->client->followRedirect();
//        $this->assertRouteSame('/home'); // Assuming the login page route is 'app_login'

        $this->assertSelectorTextContains('header>div>h1', 'Login');

        $flashMessages = $this->client->getContainer()->get('session')->getFlashBag()->get('success');
        $this->assertNotEmpty($flashMessages);
        $this->assertEquals('Login successful!', $flashMessages[0]);
        // Assert that the current page is the app_login route
        $this->assertRouteSame('');
        $this->assertSelectorTextContains('header>div>h1', 'Login');
    }
}