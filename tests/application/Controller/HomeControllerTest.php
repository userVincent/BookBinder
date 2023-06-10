<?php

namespace App\Tests\application\Controller;

use App\Controller\HomeController;
use App\Entity\Book;
use App\Entity\User;
use App\Repository\BookRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    private $client;
    private $user;
    private $user2;
    private $userRepository;
    private $crawler;

    protected function setUp(): void
    {
        parent::setUp();

        if (!$this->client) {
            $this->client = static::createClient();
            $this->client->catchExceptions(false);
        }
        $this->userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $this->user2 = $this->userRepository->findOneByEmail('tester2@gmail.com');
        // simulate $user2 being logged in
        $this->client->loginUser($this->user2);
        // Simulate a POST request to the '/favorite-book/{bookId}/{title}' URL
        $this->client->request('POST', '/favorite-book/123/book-title');
        $this->crawler = $this->client->request('GET', '/logout');

        // retrieve the test user
        $this->user = $this->userRepository->findOneByEmail('tester1@gmail.com');
        // simulate $user1 being logged in
        $this->client->loginUser($this->user);

        $this->crawler = $this->client->request('GET', '/home');
    }
    public function testResponse(): void
    {
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('#catchphrase', 'Find Books and Book Lovers!!');
        $this->assertSelectorTextContains('#search-button', 'Search');
    }

//    /**
//     * @depends testResponse
//     */
//    public function testSearchBook(): void
//    {
//        $this->assertSelectorTextContains('#search-button', 'Search');
//        $form = $this->crawler->selectButton('Search')->form();
//        $form['input'] = 'testing';
//        $this->crawler = $this->client->submit($form);
//
//    }

    /**
     * @depends testResponse
     */
    public function testFavoriteAction()
    {
        // Simulate a POST request to the '/favorite-book/{bookId}/{title}' URL
        $this->client->request('POST', '/favorite-book/123/book-title');

        // Assert that the response is a JSON response with the expected message
        $this->assertJsonStringEqualsJsonString('{"message": "Book favorited successfully"}', $this->client->getResponse()->getContent());
        // Check if the book is in database
        // Fetch the selected book based on the provided book ID
        $bookRepository = static::getContainer()->get(BookRepository::class);
        $book = $bookRepository->findOneBy(['ISBN' => '123']);
        $this->assertNotNull($book);
        $this->assertEquals('book-title',$book->getTitle());
        // Check if the book is favorited in database
        $isBookFavorited = false;
        $gotFavoriteBooks = $this->user->getFavoriteBooks();
        foreach ($gotFavoriteBooks as $favoriteBook) {
            if ($favoriteBook->getId() === $book->getId()) {
                $isBookFavorited = true;
                break;
            }
        }
        $this->assertTrue($isBookFavorited);
    }
    /**
     * @depends testFavoriteAction
     */
    public function testUnfavoriteAction()
    {
        // Simulate a POST request to the '/favorite-book/{bookId}/{title}' URL
        $this->client->request('POST', '/favorite-book/123/book-title');
        //Now the book is favorited, try to unfavorite it
        $this->client->request('POST', '/favorite-book/123/book-title');
        // Assert that the response is a JSON response with the expected message
        $this->assertJsonStringEqualsJsonString('{"message": "Book unfavorited successfully"}', $this->client->getResponse()->getContent());
        // Check the book is still in database
        $bookRepository = static::getContainer()->get(BookRepository::class);
        $book = $bookRepository->findOneBy(['ISBN' => '123']);
        $this->assertNotNull($book);
        // Check if the book is unfavorited in database
        $isUnFavorited = true;
        $gotFavoriteBooks = $this->user->getFavoriteBooks();
        foreach ($gotFavoriteBooks as $favoriteBook) {
            if ($favoriteBook->getId() === $book->getId()) {
                $isUnFavorited = false;
                break;
            }
        }
        $this->assertTrue($isUnFavorited);

    }

    /**
     * @depends testResponse
     */
    public function testCheckFavoriteNull()
    {
        // Simulate a GET request to the '/check-favorite-book/{bookId}' URL
        $this->client->request('GET', '/check-favorite-book/12345');
        // Assert that the response is a JSON response with the expected message
        $this->assertJsonStringEqualsJsonString('{"message": "Book not favorited"}', $this->client->getResponse()->getContent());
    }

    /**
     * @depends testResponse
     */
    public function testCheckFavoriteFavorited()
    {

        // Simulate a POST request to the '/favorite-book/{bookId}/{title}' URL
        $this->client->request('POST', '/favorite-book/123/book-title');
        // Simulate a GET request to the '/check-favorite-book/{bookId}' URL
        $this->client->request('GET', '/check-favorite-book/123');
        // Assert that the response is a JSON response with the expected message
        $this->assertJsonStringEqualsJsonString('{"message": "Book already favorited"}', $this->client->getResponse()->getContent());
    }
    /**
     * @depends testResponse
     */
    public function testCheckFavoriteUnfavorited()
    {
        // Simulate a POST request to the '/favorite-book/{bookId}/{title}' URL
        $this->client->request('POST', '/favorite-book/123/book-title');
        // Simulate a POST request to the '/favorite-book/{bookId}/{title}' URL
        $this->client->request('POST', '/favorite-book/123/book-title');
        // Simulate a GET request to the '/check-favorite-book/{bookId}' URL
        $this->client->request('GET', '/check-favorite-book/123');
        // Assert that the response is a JSON response with the expected message
        $this->assertJsonStringEqualsJsonString('{"message": "Book not favorited"}', $this->client->getResponse()->getContent());
    }
    public function testLogoutException()
    {
        // Create an instance of the HomeController
        $homeController = new HomeController();
        // Assert that an exception is thrown when calling the logout() method
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Don't forget to activate logout in security.yaml");

        // Call the logout() method
        $homeController->logout();
    }
    /**
     * @depends testResponse
     */
    public function testLogout()
    {

        // Assert that the user is authenticated before logging out
        $this->assertTrue($this->client->getContainer()->get('security.authorization_checker')->isGranted('ROLE_USER'));
        // Simulate a GET request to the '/logout' URL
        $this->crawler = $this->client->request('GET', '/logout');

        // Assert that the user is no longer authenticated after logging out
        $this->assertFalse($this->client->getContainer()->get('security.authorization_checker')->isGranted('ROLE_USER'));

    }

    /**
     * @depends testResponse
     */
    public function testGetProfile()
    {
        // Simulate a GET request to the '/profile' URL
        $this->crawler = $this->client->request('GET', '/profile');
        $this->assertSelectorTextContains('#subtitle','Meet book lovers');
        $this->assertSelectorTextContains('.user-name', $this->user->getFirstName());
        $this->assertSelectorTextContains('.user-name', $this->user->getLastName());
        $this->assertSelectorTextContains('.info-value', $this->user->getEmail());
//        $this->assertSelectorTextContains('.info-value', $this->user->getBirthday()->format('Y-m-d'));
    }

    /**
     * @depends testResponse
     */
    public function testGetProfilePublic()
    {
        // Simulate a GET request to the '/profile/public/{id}' URL
        $id = $this->user->getId();
        $this->client->request('GET', '/profile/public/' . $id);

        // Assert that the response is successful
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        // Assert that the response contains the expected data
        $this->assertSelectorTextContains('#subtitle','Meet book lovers');
        $this->assertSelectorTextContains('.user-name', $this->user->getFirstName());
        $this->assertSelectorTextContains('.user-name', $this->user->getLastName());
        $this->assertSelectorTextContains('.info-value', $this->user->getEmail());

    }

    /**
     * @depends testResponse
     */
    public function testGetPeopleListNull()
    {
        // Simulate a GET request to the '/peoplelist/{isbn}' URL
        $invalidISBN = '123456789'; // Replace with a invalid ISBN
        $this->client->request('GET', "/peoplelist/{$invalidISBN}");
        // Assert that the response is successful
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        // Assert that the response contains the expected data
        $this->assertSelectorTextContains('#subtitle','Meet book lovers');
        $this->assertSelectorTextContains('#genres','Young Adult, Fiction');
        $this->assertSelectorTextContains('#isbn',$invalidISBN);
        $this->assertSelectorExists('div.person-card-row');
        $this->assertEquals('', $this->client->getCrawler()->filter('div.person-card-row')->text());

    }

    /**
     * @depends testResponse
     */
    public function testGetPeopleList()
    {
        // Simulate a POST request to the '/favorite-book/{bookId}/{title}' URL
        $this->client->request('POST', '/favorite-book/123/book-title');
        // Simulate a GET request to the '/peoplelist/{isbn}' URL
        $this->client->request('GET', "/peoplelist/123");
        // Assert that the response is successful
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        // Assert that the response contains the expected data
        $this->assertSelectorTextContains('#subtitle','Meet book lovers');
        $this->assertSelectorTextContains('.data-user-name > h3',$this->user2->getFirstName());
        $this->assertSelectorTextContains('.data-user-name > h3',$this->user2->getLastName());

    }
//    /**
//     * @depends testResponse
//     */
//    public function testPeopleSelect()
//    {
//        // @TODO Finish the test after the twig file is ready
//        // Simulate a GET request to the '/profile' URL
////        $this->crawler = $this->client->request('GET', '/peopleselect');
//
//    }

    /**
     * @depends testResponse
     */
    public function testGetPeople()
    {
        // Simulate a POST request to the '/searchProfiles' URL
        $this->client->request('POST', '/searchProfiles',['searchQuery' => 'Shusaku']);

        // Assert that the response is a JSON response with the expected status code
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->headers->contains('Content-Type', 'application/json'));

        // Assert the response content
        $expectedResponse = [
            [
                'firstname' => 'Shusaku',
                'lastname' => 'Segawa',
                'id' => $this->user2->getId(),
            ],
            // Add more expected results as needed
        ];
        $this->assertJsonStringEqualsJsonString(json_encode($expectedResponse), $this->client->getResponse()->getContent());
    }

    /**
     * @depends testResponse
     */
    public function testGetPeopleNull()
    {
        // Simulate a POST request to the '/searchProfiles' URL
        $this->client->request('POST', '/searchProfiles',['searchQuery' => 'tester']);

        // Assert that the response is a JSON response with the expected status code
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->headers->contains('Content-Type', 'application/json'));

        $this->assertJsonStringEqualsJsonString('[]', $this->client->getResponse()->getContent());

    }
}