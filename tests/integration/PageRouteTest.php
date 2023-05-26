<?php

namespace App\Tests\integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PageRouteTest extends WebTestCase
{
    public function testRouteHome(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/home');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('section > h2', 'Trending Books');
        $this->assertSelectorTextContains('main > h2', 'Find Books and Book Lovers!!');
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
    }

    public function testRouteBook(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/book');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('main>h1', 'All Books');
    }



}
