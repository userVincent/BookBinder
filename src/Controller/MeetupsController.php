<?php

namespace App\Controller;

use App\Repository\LibraryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MeetupRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

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
        $meetupsPerson1 = $meetupRepository->findBy(['person1' => $user]);
        $meetupsPerson2 = $meetupRepository->findBy(['person2' => $user]);

        // Merge two arrays
        $meetups = array_merge($meetupsPerson1, $meetupsPerson2);

        return $this->render('meetups/index.html.twig', [
            'meetups' => $meetups,
            'user' => $user,
        ]);
    }

    #[Route('/meetups/arrange/{userId}', name: 'app_meetup_arrange')]
    public function arrangeMeetup(int $userId, LibraryRepository $libraryRepository): Response
    {
        return $this->render('create_meetup/index.html.twig', [
            'userIdMeetup' => $userId,
        ]);
    }

    /**
     * @Route("/meetup/delete/{id}", name="meetup_delete", methods={"POST"})
     */
    public function delete(int $id, MeetupRepository $meetupRepository, EntityManagerInterface $em)
    {
        $meetup = $meetupRepository->find($id);

        if ($meetup) {
            $em->remove($meetup);
            $em->flush();
        }

        // Redirect to the previous page or any other page
        return $this->redirect($this->generateUrl('app_meetups'));
    }

    /**
     * @Route("/meetup/accept/{id}", name="meetup_accept", methods={"POST"})
     */
    public function accept(int $id, MeetupRepository $meetupRepository, EntityManagerInterface $em)
    {
        $meetup = $meetupRepository->find($id);

        if ($meetup) {
            $meetup->setState(0);
            $em->persist($meetup);
            $em->flush();
        }

        // Redirect to the previous page or any other page
        return $this->redirect($this->generateUrl('app_meetups'));
    }
}
