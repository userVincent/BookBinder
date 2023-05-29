<?php

namespace App\Controller;

use App\Repository\LibraryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MeetupRepository;
use App\Repository\UserRepository;

class MeetupsController extends AbstractController
{
    #[Route('/meetups', name: 'app_meetups')]
    public function index(MeetupRepository $meetupRepository, UserRepository $userRepository, LibraryRepository $libraryRepository): Response
    {
        $user = $this->getUser();

        // Get user entity
        $userEntity = $userRepository->find($user);
        
        if(!$user) {
            return $this->render('meetups/index.html.twig', [
                'meetups' => null,
            ]);
        }

        // Find meetups where person1Id or person2Id is equal to $user
        $meetups = $meetupRepository->findBy(['person1' => $userEntity->getId()]) + $meetupRepository->findBy(['person2' => $userEntity->getId()]);

        return $this->render('meetups/index.html.twig', [
            'meetups' => $meetups,
        ]);
    }
}
