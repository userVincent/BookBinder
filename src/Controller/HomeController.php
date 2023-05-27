<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\BookRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(BookRepository $bookRepository): Response
    {
        //$books = $bookRepository->findBy([], null, 10);
        $user = $this->getUser();

        //dd($books);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            //'trending_books' => $books,
            'user' => $user,
        ]);
    }

    #[Route('/search-books', name: 'app_search_books')]
    public function searchBooks(Request $request, BookRepository $bookRepository): Response
    {
        $query = $request->query->get('query');
        $books = $bookRepository->searchBooks($query);

        //dd($books);

        return $this->render('book/index.html.twig', [
            'books' => $books,
        ]);
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
            'email' => $user->getEmail(),
            'address' => $user->getAddress(),
            'birthday' => $user->getBirthday()->format('Y-m-d'),
        ]);
    }
}
