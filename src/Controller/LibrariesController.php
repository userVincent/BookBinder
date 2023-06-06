<?php

namespace App\Controller;

use App\Entity\Library;
use App\Repository\LibraryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class LibrariesController extends AbstractController
{
    #[Route('/libraries/data', name: 'app_libraries_data')]
    public function data(Request $request, LibraryRepository $libraryRepository): Response
    {
        // This is the code you already have for fetching libraries
        $page = $request->query->getInt('page', 1);
        $size = $request->query->getInt('size', 10);
        $offset = ($page - 1) * $size;

        $libraries = $libraryRepository->findBy([], null, $size, $offset);

        //dump($libraries); 

        $libraryData = [];
        foreach ($libraries as $library) {
            $libraryData[] = [
                'id' => $library->getId(),
                'name' => $library->getName(),
                'StreetName' => $library->getStreetName(),
                'HouseNumber' => $library->getHouseNumber(),
                'PostalCode' => $library->getPostalCode(),
                'Town' => $library->getTown(),
                // ... Other properties ...
            ];
        }

        //dump($libraryData); 

        return $this->json([
            'data' => $libraryData,
        ]);
    }

    #[Route('/libraries', name: 'app_libraries')]
    public function index(Request $request): Response
    {
        // Render the HTML page
        return $this->render('libraries/index.html.twig', [
        ]);
    }

    #[Route('/library/{id}', name: 'library_show')]
    public function show(Request $request, Library $library): Response
    {
        $userIdMeetup = $request->query->getInt('userIdMeetup', -1);
        //dump($userIdMeetup);

        return $this->render('libraries/library.html.twig', [
        'library' => $library,
        'userIdMeetup' => $userIdMeetup,
    ]);
    }
}
