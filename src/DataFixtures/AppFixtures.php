<?php
// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\Library;
use App\Entity\Meetup;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    // ...
    public function load(ObjectManager $manager)
    {
        $user1 = new User();
        $user1->setEmail('tester1@gmail.com');

        $password1 = $this->hasher->hashPassword($user1, '123456');
        $user1->setPassword($password1);
        // Create a new instance of the User class
        // Set test values using the setter methods
        $user1->setFirstName('Aocheng');
        $user1->setLastName('Zhao');

        $user1->setAddress('Leuven');
        $birthday1 = new DateTime('2001-01-01'); // Replace with the desired birthday date
        $user1->setBirthday($birthday1);
        $user1->setAbout('This is about');
        $user1->setInterests('testing');
        $user1->setAge(10);
        ///
        $filePath = 'public/uploads/profilepics/young-man-avatar-character-vector-14213210-6475001bcba56016513834.jpg';
        $file = new File($filePath);
        $user1->setProfilepicFile($file);
        $user1->setProfilepicFilename('young-man-avatar-character-vector-14213210-6475001bcba56016513834.jpg');
        $user1->setProfilepicSize(10000);
        ///
        $roles = ['ROLE_ADMIN', 'ROLE_USER'];
        $user1->setRoles($roles);


        // Assuming you have a User instance for person2
        $user2 = new User();
        $user2->setEmail('tester2@gmail.com');
        $user2->setFirstName('Shusaku');
        $user2->setLastName('Segawa');
        $password2 = $this->hasher->hashPassword($user2, '123456');
        $user2->setPassword($password2);
        $user2->setAddress('Leuven');
        $birthday2 = new DateTime('2002-01-01'); // Replace with the desired birthday date
        $user2->setBirthday($birthday2);
        $user2->setRoles(['ROLE_USER']);


        $manager->persist($user1);
        $manager->persist($user2);
        $manager->flush();
    }
}