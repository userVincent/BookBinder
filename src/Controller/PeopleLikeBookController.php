<?php

namespace App\Controller;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PeopleLikeBookController extends AbstractController
{
    #[Route('/people-like/{$id}', name: 'app_people_like_book')]
    public function show(string $isbn, EntityManagerInterface $entityManager): Response
    {
        $bookRepository = $entityManager->getRepository(Book::class);
        $book = $bookRepository->findOneBy(['isbn' => $isbn]);

        $peopleLikeBook = $book->getUsers();

        return $this->render('people_like_book/index.html.twig', [
            'people' => $peopleLikeBook,
        ]);
    }
}
