<?php


namespace App\Tests\integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PageRouteTest extends WebTestCase
{
    public function testRouteHome(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/home');
        // $this->assertTrue($client->getResponse()->isSuccessful());
        // Or with the WebTestCase helper method :
        $this->assertResponseIsSuccessful();


        // $this->assertTrue(
        //     str_contains(
        //         $crawler->filter('section > h2')->innerText(),
        //         'Welcome to the CouBooks website'
        //     )
        // );
        // Or with the WebTestCase helper method :
        $this->assertSelectorTextContains('section > h2', 'Welcome to the CouBooks website');
    }

    public function testRouteAbout(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/about');
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('section > h2', 'About this website...');
    }

    public function testRouteCourses(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/courses');
        $this->assertResponseIsSuccessful();

        $bookItems = $crawler->filter('li.bookItem');
        $this->assertEquals(11, $bookItems->count());

        $this->assertEquals(
            "Design with Operational Amplifiers and Analog Integrated Circuits",
            $bookItems->last()->innerText()
        );
    }

    public function testRouteFeedback(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/feedback');
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('section > h3', 'add feedback...');

        // fill out and submit a form
        $form = $crawler->selectButton('Submit Feedback')->form();
        $form['form[author]'] = 'Koen';
        $form['form[text]'] = "Demo";
        $client->submit($form);
        $this->assertResponseRedirects('/');
        $crawler = $client->followRedirect();

        // check that the new feedback is present on the main page
        $lastFeedback = $crawler->filter('aside');
        $this->assertStringContainsString("Demo (Koen)", $lastFeedback->text());
    }

    public function testRouteReservation(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/reservation');
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('section > h3', 'Book reservation');
    }

}
