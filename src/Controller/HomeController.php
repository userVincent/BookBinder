<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findBy([], null, 10);
        $user = $this->getUser();

        //dd($books);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'trending_books' => $books,
            'user' => $user,
        ]);
    }
}
