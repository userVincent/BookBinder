<?php

namespace App\Tests\application\Controller;

use App\Controller\HomeController;
use App\Entity\Book;
use App\Entity\User;
use App\Repository\BookRepository;
use App\Repository\LibraryRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
class LibrariesControllerTest extends WebTestCase
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

        $this->crawler = $this->client->request('GET', '/libraries');

    }

    public function testResponse(): void
    {
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('#subtitle', 'Meet book lovers');
        $this->assertSelectorTextContains('#search-button', 'Search');
        $this->assertSelectorTextContains('h3', 'Search for a library');
    }

    /**
     * @depends testResponse
     */
    public function testGetDataWithoutName(): void
    {
        // Simulate a GET request to the '/libraries/data' URL
        $this->client->request('GET', '/libraries/data', ['page' => 1, 'size' => 10]);

        // Assert that the response is a JSON response with the expected status code
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->headers->contains('Content-Type', 'application/json'));
        $size = 10;
        $page = 1;
        $offset = ($page - 1) * $size;
        $libraries = $this->libraryRepository->findBy([], null, $size, $offset);

        // Assert the response content
        $expectedData = [];
        foreach ($libraries as $library) {
            $expectedData[] = [
                'id' => $library->getId(),
                'name' => $library->getName(),
                'StreetName' => $library->getStreetName(),
                'HouseNumber' => $library->getHouseNumber(),
                'PostalCode' => $library->getPostalCode(),
                'Town' => $library->getTown(),
                // ... Other properties ...
            ];
        }
        $expectedResponse = [
            'data' => $expectedData,
        ];
        $this->assertJsonStringEqualsJsonString(json_encode($expectedResponse), $this->client->getResponse()->getContent());
    }

//    /**
//     * @depends testResponse
//     */
//    public function testGetDataWithName(): void
//    {
//        //@TODO finish this test when the function is fixed
//        // Simulate a GET request to the '/libraries/data' URL
//        $this->client->request('GET', '/libraries/data', ['page' => 1, 'size' => 10, 'name'=>'Filiaal Erembodegem']);
//
//        // Assert that the response is a JSON response with the expected status code
//        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
//        $this->assertTrue($this->client->getResponse()->headers->contains('Content-Type', 'application/json'));
//        $name = 'Filiaal Erembodegem';
//        $size = 10;
//        $page = 1;
//        $offset = ($page - 1) * $size;
//
//        $libraries = $this->libraryRepository->findByName($name, $size, $offset);
//
//        // Assert the response content
//        $expectedData = [];
//        foreach ($libraries as $library) {
//            $expectedData[] = [
//                'id' => $library->getId(),
//                'name' => $library->getName(),
//                'StreetName' => $library->getStreetName(),
//                'HouseNumber' => $library->getHouseNumber(),
//                'PostalCode' => $library->getPostalCode(),
//                'Town' => $library->getTown(),
//                // ... Other properties ...
//            ];
//        }
//        $expectedResponse = [
//            'data' => $expectedData,
//        ];
//        $this->assertJsonStringEqualsJsonString(json_encode($expectedResponse), $this->client->getResponse()->getContent());
//    }
    /**
     * @depends testResponse
     */
    public function testShow()
    {

        // Simulate a GET request to the '/library/{id}' URL
        $this->client->request('GET', '/library/100', ['userIdMeetup' => 123]);

        // Assert that the response is successful (HTTP 200 status code)
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // Assert the expected template is rendered
        $this->assertSelectorTextContains('main > h1', 'Uitleenpost Ursel');

    }

}