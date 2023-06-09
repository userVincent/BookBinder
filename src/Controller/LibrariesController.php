<?php

namespace App\Controller;

use App\Entity\Library;
use App\Repository\LibraryRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $name = $request->query->get('name');

        if($name == null){
            $libraries = $libraryRepository->findBy([], null, $size, $offset);
        }
        else{
            // name should be a substring of the library name
            $libraries = $libraryRepository->findByName($name, $size, $offset);
        }

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

    // Change to search library
    /*    #[Route('/searchLibrary', name: 'app_library_search', methods: ['GET', 'POST'])]
    public function getLibrary(Request $request, LibraryRepository $libraryRepository): JsonResponse
    {
        $searchQuery = $request->request->get('searchQuery');
        $libraries = $libraryRepository->searchLibrary($searchQuery);

        if (empty($users)) {
            return new JsonResponse([]);
        }

        $results = [];
        foreach ($users as $user) {
            $results[] = [
                'firstname' => $user->getFirstName(),
                'lastname' => $user->getLastName(),
                'address' => $user->getAddress(),
                'email' => $user->getEmail(),
            ];
        }

        return new JsonResponse($results);
    }*/

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
