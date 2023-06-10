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
use App\Entity\Meetup;
use App\Entity\User;
use App\Entity\Library;

class MeetupsController extends AbstractController
{
    // MEETUPS PAGE
    //----------------------------------------------------------------------------------
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
    //----------------------------------------------------------------------------------

    
    // ARRANGE A NEW MEETUP (FORUM)
    //----------------------------------------------------------------------------------
    #[Route('/meetups/arrange/date_time', name: 'date_time_select')]
    public function arrangeMeetup(SessionInterface $session): Response
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

    // FORWARD LIBRARY SELECT (FROM SELECING DATE AND TIME)
    #[Route('/meetups/arrange/library_select', name: 'library_select', methods: ['POST', 'GET'])]
    public function librarySelect(Request $request, SessionInterface $session): Response
    {
        // Get datetime from form
        $datetime = new \DateTime($request->request->get('datetime'));

        $date = $datetime->format('Y-m-d');
        $time = $datetime->format('H:i');


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

    // BACKWARD LIBRARY SELECT (FROM SELECING PERSON)
    #[Route('/meetups/arrange/library_select', name: 'library_select_backward')]
    public function selectLibrary(): Response
    {
        // Render the HTML page
        return $this->render('libraries/library_select.html.twig', [
        ]);
    }

    // FORWARD PERSON SELECT (FROM SELECING LIBRARY)
    #[Route('/meetups/arrange/person_select/{libraryId}', name: 'person_select')]
    public function personSelect($libraryId, SessionInterface $session, Request $request): Response
    {
        $referer = $request->headers->get('referer');

        // Check if we came from '/meetups/arrange/library_select'
        if(strpos($referer, '/meetups/arrange/library_select') !== false) {
            // Save library ID to session
            $meetupData = $session->get('meetupData', []);
            $meetupData['libraryId'] = $libraryId;
            $session->set('meetupData', $meetupData);
        }

        // Redirect to actual person selection page
        return $this->render('meetups/people_select.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    // FORWARD CREATING MEETUP (FROM SELECING PERSON)
    #[Route('/meetups/arrange/create_meetup/{userId}', name: 'create_meetup', methods: ['POST', 'GET'])]
    public function createMeetup($userId, SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        // get all date from session
        $meetupDateString = $session->get('meetupData')['meetupDate'];
        $meetupTimeString = $session->get('meetupData')['meetupTime'];
        $meetupLibraryId =  $session->get('meetupData')['libraryId'];
        //var_dump($session->get('meetupData'));
        //die();

        if (!$meetupLibraryId) {
            throw new \Exception('Library ID not set in session');
        }

        // convert date and time strings to DateTime
        $meetupDate = \DateTime::createFromFormat('Y-m-d', $meetupDateString);
        $meetupTime = \DateTime::createFromFormat('H:i', $meetupTimeString);

        //convert library ID to library entity
        $library = $entityManager->getRepository(Library::class)->find($meetupLibraryId);
        if (!$library) {
            throw $this->createNotFoundException(
                'No library found for id ' . $meetupLibraryId
            );
        }
        
        // Get the person2 entity from the user ID
        $user2 = $entityManager->getRepository(User::class)->find($userId);

        // Get person1 ID from logged in user
        $user1 = $this->getUser();

        /*
        if (!$user1 || !$user2 || !$meetupDate || !$meetupTime || !$meetupLibraryId) {
            throw $this->createNotFoundException(
                'No user/library found for id '
            );
        }*/

        $meetup = new Meetup();
        $meetup->setPerson1($user1);
        $meetup->setPerson2($user2);
        $meetup->setLibrary($library);
        $meetup->setDate($meetupDate);
        $meetup->setTime($meetupTime);
        $meetup->setState(2); // 0 = accepted/upcoming, 1 = accepetd/past, 2 = pending
        $entityManager->persist($meetup);
        $entityManager->flush();

        return $this->redirectToRoute('app_meetups');

    }
    //----------------------------------------------------------------------------------

}
