<?php

namespace App\Controller;

use App\Repository\LibraryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Library;

class CreateMeetupController extends AbstractController
{
    #[Route('/library/select', name: 'library_select')]
    public function selectLibrary(): Response
    {
        // Render the HTML page
        return $this->render('libraries/library_select.html.twig', [
        ]);
    }
}
