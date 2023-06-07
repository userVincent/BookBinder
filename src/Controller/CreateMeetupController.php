<?php

namespace App\Controller;

use App\Repository\LibraryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Library;

class CreateMeetupController extends AbstractController
{
    #[Route('/create/meetup', name: 'app_create_meetup')]
    public function index(): Response
    {
        return $this->render('create_meetup/index.html.twig', [
            'controller_name' => 'CreateMeetupController',
        ]);
    }

    #[Route('/library/select', name: 'library_select')]
    public function selectLibrary(): Response
    {
        // Render the HTML page
        return $this->render('libraries/library_select.html.twig', [
        ]);
    }
}
