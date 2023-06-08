<?php

namespace App\Controller;

use App\Repository\LibraryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MeetupRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;


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
    
    #[Route('/meetups/arrange', name: 'app_meetup_arrange')]
    public function arrangeMeetup(LibraryRepository $libraryRepository, SessionInterface $session): Response
    {
        // Clear old data if exists
        if ($session->has('meetupData')) {
            $session->remove('meetupData');
        }

        // Initialize your meetupData
        $meetupData = []; // populate this array with your meetup data

        // Set the new session data
        $session->set('meetupData', $meetupData);

        return $this->render('create_meetup/index.html.twig');
    }

    #[Route('/meetups/arrange/library_select', name: 'library_select', methods: ['POST'])]
    public function librarySelect(Request $request, SessionInterface $session): Response
    {
        // Get datetime from form
        $datetime = $request->request->get('datetime');

        // Parse datetime string into date and time
        $datetimeParsed = date_parse($datetime);

        $date = sprintf(
            '%04d-%02d-%02d', 
            $datetimeParsed['year'], 
            $datetimeParsed['month'], 
            $datetimeParsed['day']
        );
        $time = sprintf(
            '%02d:%02d', 
            $datetimeParsed['hour'], 
            $datetimeParsed['minute']
        );

        // Get current meetupData from session
        $meetupData = $session->get('meetupData', []);

        // Save date and time to meetupData
        $meetupData['meetupDate'] = $date;
        $meetupData['meetupTime'] = $time;

        // Save updated meetupData back to session
        $session->set('meetupData', $meetupData);

        // Redirect to actual library selection page
        return $this->render('/libraries/library_select.html.twig');
    }

    #[Route('/meetups/arrange/person_select', name: 'person_select', methods: ['POST'])]
    public function personSelect(Request $request, SessionInterface $session): Response
    {
        // Get the library ID from form
        $libraryId = $request->request->get('libraryId');

        // Save library ID to session
        $session->set('meetupLibraryId', $libraryId);

        // Redirect to actual person selection page
        return $this->render('/person/person_select.html.twig');
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
