<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MeetupController extends AbstractController
{
    #[Route('/meetup', name: 'app_meetup')]
    public function index(): Response
    {
        return $this->render('meetup/index.html.twig', [
            'controller_name' => 'MeetupController',
        ]);
    }
}
