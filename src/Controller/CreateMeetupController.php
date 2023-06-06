<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateMeetupController extends AbstractController
{
    #[Route('/create/meetup', name: 'app_create_meetup')]
    public function index(): Response
    {
        return $this->render('create_meetup/index.html.twig', [
            'controller_name' => 'CreateMeetupController',
        ]);
    }
}
