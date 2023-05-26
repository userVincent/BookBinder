<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Repository\LibraryRepository;
use App\Entity\User;
use App\Entity\Library;
use App\Entity\Meetup;

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

    #[Route('/meetups/arrange/{userId}', name: 'app_meetup_arrange')]
    public function arrangeMeetup(int $userId, LibraryRepository $libraryRepository): Response
    {
        $libraries = $libraryRepository->findAll();

        return $this->render('meetups/arrange.html.twig', [
            'libraries' => $libraries,
            'userId' => $userId,
        ]);
    }

    #[Route('/meetups/create/{user1Id}/{user2Id}/{libraryId}', name: 'app_meetup_create', methods: ['POST'])]
    public function createMeetup(EntityManagerInterface $entityManager, UserRepository $userRepository, LibraryRepository $libraryRepository, int $user1Id, int $user2Id, int $libraryId): Response
    {
        $user1 = $userRepository->find($user1Id);
        $user2 = $userRepository->find($user2Id);
        $library = $libraryRepository->find($libraryId);

        if (!$user1 || !$user2 || !$library) {
            throw $this->createNotFoundException(
                'No user/library found for id '.$user1Id.'/'.$user2Id.'/'.$libraryId
            );
        }

        $meetup = new Meetup();
        $meetup->setPerson1($user1);
        $meetup->setPerson2($user2);
        $meetup->setLibrary($library);
        $meetup->setDate(new \DateTime());
        $meetup->setTime(new \DateTime());

        $entityManager->persist($meetup);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Meetup created successfully!']);
    }
}
