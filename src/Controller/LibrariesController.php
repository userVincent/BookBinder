<?php

namespace App\Controller;

use App\Entity\Library;
use App\Repository\LibraryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LibrariesController extends AbstractController
{
    #[Route('/libraries', name: 'app_libraries')]
    public function index(LibraryRepository $libraryRepository): Response
    {
        $libraries = $libraryRepository->findAll();

        return $this->render('libraries/index.html.twig', [
            'controller_name' => 'LibrariesController',
            'libraries' => $libraries,
        ]);
    }

    #[Route('/library/{id}', name: 'library_show')]
    public function show(Library $library): Response
    {
        return $this->render('libraries/library.html.twig', [
        'library' => $library,
    ]);
    }
}
