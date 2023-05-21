<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookprofileController extends AbstractController
{
    #[Route('/bookprofile', name: 'app_bookprofile')]
    public function index(): Response
    {
        return $this->render('bookprofile/index.html.twig', [
            'controller_name' => 'BookprofileController',
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
}
