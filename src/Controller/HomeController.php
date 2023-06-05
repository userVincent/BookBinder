<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\User;
use App\Repository\BookRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(BookRepository $bookRepository): Response
    {
        //$books = $bookRepository->findBy([], null, 10);
        $user = $this->getUser();
        $this->denyAccessUnlessGranted('ROLE_USER');
        //dd($books);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            //'trending_books' => $books,
            'user' => $user,
        ]);
    }



    //To add a book to favorites or to remove it
    #[Route('/favorite-book/{bookId}/{title}', name: 'app_favorite-book', methods: ['POST'])]
    public function favoriteAction(String $bookId, String $title, EntityManagerInterface $entityManager): JsonResponse
    {
        // Retrieve the current user from the session or authentication context
        $user = $this->getUser();

        // Get the book ID from the request
        $ISBN = $bookId;

        // Fetch the selected book based on the provided book ID
        $book = $entityManager->getRepository(Book::class)->findOneBy(['ISBN' => $ISBN]);

        if (!$book) {
            // Handle the case when the book is not found
            // Book doesn't exist, create a new instance and set its ISBN
            $book = new Book();
            $book->setISBN($ISBN);
            $book->setTitle($title);
            $entityManager->persist($book);
            $entityManager->flush();
//            return new JsonResponse(['message' => 'Book not found'], 404);
        }


        // Check if the user has already favorited the book
        $isFavorited = $user->getFavoriteBooks()->contains($book);

        if ($isFavorited) {
            // Remove the book from the user's favorites
            $user->removeFavoriteBook($book);
            $message = 'Book unfavorited successfully';
        } else {
            // Add the book to the user's favorites
            $user->addFavoriteBook($book);
            $message = 'Book favorited successfully';
        }

        $entityManager->flush();

        return new JsonResponse(['message' => $message]);
    }

    #[Route('/check-favorite-book/{bookId}', name: 'app_check-favorite-book')]
    public function checkFavorite(String $bookId, EntityManagerInterface $entityManager): JsonResponse
    {
        // Retrieve the current user from the session or authentication context
        $user = $this->getUser();

        // Get the book ID from the request
        $ISBN = $bookId;

        // Fetch the selected book based on the provided book ID
        $book = $entityManager->getRepository(Book::class)->findOneBy(['ISBN' => $ISBN]);

        if (!$book) {
            // Handle the case when the book is not found
            return new JsonResponse(['message' => 'Book not favorited']);
        }


        // Check if the user has already favorited the book
        $isFavorited = $user->getFavoriteBooks()->contains($book);

        if ($isFavorited) {
            $message = 'Book already favorited';
        } else {
            // Add the book to the user's favorites
            $message = 'Book not favorited';
        }

        return new JsonResponse(['message' => $message]);
    }

    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout()
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    #[Route('/profile', name: 'app_user_profile')]
    public function getProfile(Request $request, UserRepository $userRepository): Response
    {
        $user = $this->getUser();

        return $this->render('user_profile_private/index.html.twig', [
            'controller_name' => 'HomeController',
            'user' => $user,
            'firstname' => $user->getFirstName(),
            'lastname' => $user->getLastName(),
            'profilePictureFilename' => $user->getProfilepicFilename(),
            'email' => $user->getEmail(),
            'address' => $user->getAddress(),
            'birthday' => $user->getBirthday()->format('Y-m-d'),
        ]);
    }

    #[Route('/profile/public/{id}', name: 'app_user_profile_public')]
    public function getProfilePublic(Request $request, $id, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);

        return $this->render('user_profile_public/index.html.twig', [
            'controller_name' => 'HomeController',
            'user' => $user,
            'firstname' => $user->getFirstName(),
            'lastname' => $user->getLastName(),
            'email' => $user->getEmail(),
            'address' => $user->getAddress(),
            'birthday' => $user->getBirthday()->format('Y-m-d'),
        ]);
    }

    #[Route('/peoplelist/{isbn}', name: 'app_user_peoplelist')]
    public function getPeopleList(Request $request, $isbn, BookRepository $bookRepository): Response
    {
        // Assuming there is a method to find the book by ISBN in your repository
        $book = $bookRepository->findOneBy(['ISBN' => $isbn]);
        if ($book != null){
            $users = $book->getUsers();

            //Ensure that if the user has favorited that book it doesn't show his own profile
            $current_user = $this->getUser();
            if ($users->contains($current_user)){
                $users->removeElement($current_user);
            }
        }
        else {
            $users = null;
        }


        return $this->render('people_list/index.html.twig', [
            'controller_name' => 'HomeController',
            'isbn' => $isbn,
            'users' => $users,
        ]);
    }

    #[Route('/peopleselect', name: 'app_people_select')]
    public function selectPeople(Request $request, UserRepository $userRepository): Response
    {

        return $this->render('meetup/people_select.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }


    #[Route('/searchProfiles', name: 'app_people_search', methods: ['GET', 'POST'])]
    public function getPeople(Request $request, UserRepository $userRepository): JsonResponse
    {
        $searchQuery = $request->request->get('searchQuery');
        $users = $userRepository->searchUsers($searchQuery);

        if (empty($users)) {
            return new JsonResponse([]);
        }

        $results = [];
        foreach ($users as $user) {
            $results[] = [
                'firstname' => $user->getFirstName(),
                'lastname' => $user->getLastName(),
                'id' => $user->getId(),
            ];
        }

        return new JsonResponse($results);
    }
}
