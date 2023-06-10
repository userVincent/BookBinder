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
            '_username' => 'tester1@gmail.com',
            '_password' => '123456',
        ]);

        $this->client->submit($form);
        // Follow the redirection
        $this->crawler = $this->client->followRedirect();

        $headerText = $this->crawler->filter('#catchphrase')->text();
        echo $headerText;

        $this->assertSelectorTextContains('#catchphrase', 'Find Books and Book Lovers!!');

        // Assert that the current route matches the expected one
        $this->assertRouteSame('app_home');

    }
}