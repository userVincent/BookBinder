<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\User;
use App\Repository\BookRepository;
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
    #[Route('/favorite-book/{bookId}', name: 'app_favorite-book', methods: ['POST'])]
    public function favoriteAction(String $bookId, EntityManagerInterface $entityManager): JsonResponse
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
}
