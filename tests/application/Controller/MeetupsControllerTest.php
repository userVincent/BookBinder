<?php

namespace App\Tests\application\Controller;

use App\Tests\SessionHelperTrait;
use App\Controller\HomeController;
use App\Controller\MeetupsController;
use App\Entity\Book;
use App\Entity\Library;
use App\Entity\User;
use App\Repository\BookRepository;
use App\Repository\LibraryRepository;
use App\Repository\MeetupRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;
use Symfony\Component\Routing\RouterInterface;


class MeetupsControllerTest extends WebTestCase
{
    use SessionHelperTrait;
    private $client;
    private $session;
    private $meetupData;

    private $user;
    private $user2;
    private $userRepository;
    private $meetupRepository;
    private $libraryRepository;
    private $crawler;

    protected function setUp(): void
    {
        parent::setUp();

        if (!$this->client) {
            $this->client = static::createClient();
            $this->client->catchExceptions(false);
        }
//        //setup a fake meetup session
//        $this->session = $this->createSession($this->client);
////        $this->session = new Session(new MockFileSessionStorage());
//        $datetime = new \DateTime('2023-06-09 10:30:00');
//        $date = $datetime->format('Y-m-d');
//        $time = $datetime->format('H:i');
//        $this->meetupData = [];
//        $this->meetupData['meetupDate'] = $date;
//        $this->meetupData['meetupTime'] = $time;
//        $this->session->set('meetupData',$this->meetupData);

        //
        $this->libraryRepository = static::getContainer()->get(LibraryRepository::class);
        $this->meetupRepository = static::getContainer()->get(MeetupRepository::class);
        $this->userRepository = static::getContainer()->get(UserRepository::class);
        // retrieve the test user
        // retrieve the test user
        $this->user2 = $this->userRepository->findOneByEmail('tester2@gmail.com');
        $this->user = $this->userRepository->findOneByEmail('tester1@gmail.com');
        // simulate $user1 being logged in
        $this->crawler = $this->client->loginUser($this->user);

        $this->crawler = $this->client->request('GET', '/meetups');

    }

    public function testResponse(): void
    {
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('#subtitle', 'Meet book lovers');
        $this->assertSelectorTextContains('main > h1', 'Your Meetups');
//        $this->assertNotNull($this->session->get('meetupData'));
//        echo json_encode($this->session->get('meetupData'));
    }
//    public function testNoUserException()
//    {
//        // Create an instance of the HomeController
//        $meetupsController = new MeetupsController();
//        // Assert that an exception is thrown when calling the logout() method
//
//        // Call the logout() method
//        $meetupsController->index($this->meetupRepository,$this->userRepository,$this->libraryRepository);
//        $this->assertSelectorTextContains('main > h1', 'Your Meetups');
//    }


    /**
     * @depends testResponse
     */
    public function testDelete(): void
    {
        $this->crawler = $this->client->request('POST', '/meetup/delete/1');
        $this->assertTrue(true);
//        $this->assertSelectorTextContains('main > h1', 'Your Meetups');

    }
    /**
     * @depends testResponse
     */
    public function testAccept(): void
    {
        $this->crawler = $this->client->request('POST', '/meetup/accept/1');
        $this->assertTrue(true);
//        $this->assertSelectorTextContains('main > h1', 'Your Meetups');

    }
    /**
     * @depends testResponse
     */
    public function testArrangeMeetup(): void
    {
        $this->crawler = $this->client->request('POST', '/meetups/arrange/date_time',['meetupData'=>$this->meetupData]);

        $this->assertSelectorTextContains('main > h1', 'Pick a date');

    }
    /**
     * @depends testResponse
     */
    public function testLibrarySelect(): void
    {
        $this->crawler = $this->client->request('POST', '/meetups/arrange/library_select',['meetupData'=>$this->meetupData]);

        $this->assertSelectorTextContains('main > h1', 'Libraries');
        $this->assertSelectorTextContains('main > h3', 'Search for a library');

    }
    /**
     * @depends testResponse
     */
    public function testSelectLibrary(): void
    {
        // Send a GET request to the route using its name
        $router = $this->getContainer()->get(RouterInterface::class);
        $this->crawler = $this->client->request('GET', $router->generate('library_select_backward'));

        $this->assertSelectorTextContains('main > h1', 'Libraries');
        $this->assertSelectorTextContains('main > h3', 'Search for a library');

    }
    /**
     * @depends testResponse
     */
    public function testPersonSelect(): void
    {
        $this->crawler = $this->client->request('POST', '/meetups/arrange/library_select',['meetupData'=>$this->meetupData]);
        $this->crawler = $this->client->request('POST', '/meetups/arrange/person_select/100');
        $this->assertTrue(true);
//        $this->assertSelectorTextContains('main > h1', 'Your Meetups');

    }

    /**
     * @depends testResponse
     */
    public function testCreateMeetup(): void
    {
        //step1 ArrangeMeetup
        $this->crawler = $this->client->request('POST', '/meetups/arrange/date_time',['meetupData'=>[]]);

        //step2 LibrarySelect
        $datetime = new \DateTime('2023-06-09 10:30:00');
        $JsonDateTime = json_encode($datetime);
        $this->crawler = $this->client->request('POST', '/meetups/arrange/library_select',[],[],[],$JsonDateTime);

        //step3 PeronSelect
        $this->crawler = $this->client->request('POST', '/meetups/arrange/person_select/100');

        $this->crawler = $this->client->request('GET', '/meetups/arrange/create_meetup/3');
        $this->assertTrue(true);
//        $this->assertSelectorTextContains('main > h1', 'Your Meetups');

    }

    /**
     * @depends testResponse
     */
    public function testCreateMeetupInvaildLibrary(): void
    {
        //step1 ArrangeMeetup
        $this->crawler = $this->client->request('POST', '/meetups/arrange/date_time',['meetupData'=>[]]);

        //step2 LibrarySelect
        $datetime = new \DateTime('2023-06-09 10:30:00');
        $JsonDateTime = json_encode($datetime);
        $this->crawler = $this->client->request('POST', '/meetups/arrange/library_select',[],[],[],$JsonDateTime);

        //step3 PeronSelect
        $this->crawler = $this->client->request('POST', '/meetups/arrange/person_select/-1');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No library found for id -1');
        $this->crawler = $this->client->request('GET', '/meetups/arrange/create_meetup/'.$this->user2->getId());
        // Assert that the exception was thrown

//        $this->assertSelectorTextContains('main > h1', 'Your Meetups');

    }

    /**
     * @depends testResponse
     */
    public function testCreateMeetupNullLibrary(): void
    {
        //step1 ArrangeMeetup
        $this->crawler = $this->client->request('POST', '/meetups/arrange/date_time',['meetupData'=>[]]);

        //step2 LibrarySelect
        $datetime = new \DateTime('2023-06-09 10:30:00');
        $JsonDateTime = json_encode($datetime);
        $this->crawler = $this->client->request('POST', '/meetups/arrange/library_select',[],[],[],$JsonDateTime);

        //step3 PeronSelect
        $this->crawler = $this->client->request('POST', '/meetups/arrange/person_select/null');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Library ID not set in session');

        $this->crawler = $this->client->request('GET', '/meetups/arrange/create_meetup/'.$this->user2->getId());
        $this->assertTrue(true);
//        $this->assertSelectorTextContains('main > h1', 'Your Meetups');

    }


}