<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Entity\User;

class ProfileController extends AbstractController
{
    #[Route('/profiles', name: 'app_profiles')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('profile/profiles.html.twig', [
            'controller_name' => 'ProfileController',
            'users' => $users,
        ]);
    }

    #[Route('/profile/{id}', name: 'profile_show')]
    public function show(User $user): Response
    {
        return $this->render('profile/profile.html.twig', [
        'user' => $user,
    ]);
    }
}
