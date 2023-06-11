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
use Doctrine\ORM\EntityManagerInterface;

class LibrariesController extends AbstractController
{
    #[Route('/libraries/data', name: 'app_libraries_data')]
    public function data(Request $request, LibraryRepository $libraryRepository): Response
    {
        // This is the code you already have for fetching libraries
        $page = $request->query->getInt('page', 1);
        $size = $request->query->getInt('size', 10);
        $offset = ($page - 1) * $size;
        $town = $request->query->get('town');

        if($town == null){
            $libraries = $libraryRepository->findDistinctNames( $size, $offset);
        }
        else{
            // town should be a substring of the library town
            $libraries = $libraryRepository->findByTown($town, $size, $offset);
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
                'Longitude' => $library->getLongitude(),
                'Latitude' => $library->getLatitude(),
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

    #[Route('/libraries/update_coordinates', name: 'update_coordinates', methods: ['POST'])]
    public function update_coordinates(Request $request, EntityManagerInterface $em): Response
    {
        $data = json_decode($request->getContent(), true);
        $libraryId = $data['libraryId'];
        $latitude = $data['latitude'];
        $longitude = $data['longitude'];

        $library = $em->getRepository(Library::class)->find($libraryId);

        if (!$library) {
            return $this->json(['error' => 'Library not found'], 404);
        }

        $library->setLatitude($latitude);
        $library->setLongitude($longitude);

        $em->flush();

        return $this->json(['message' => 'Coordinates updated successfully']);
    }
}
