<?php

namespace App\Tests\application\Panther;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Panther\PantherTestCase;

class RegistrationPostTest extends PantherTestCase
{
    private $entityManager;
    private $user;
    private $userRepository;
    protected function setUp(): void
    {
//        $kernel = self::bootKernel();
//
//        $this->entityManager = $kernel->getContainer()
//            ->get('doctrine')
//            ->getManager();
//        $this->userRepository = $this->entityManager->getRepository(User::class);
    }

    public function testRegistration(): void
    {
        $client = self::createPantherClient(['browser' => 'chrome']);
        $crawler = $client->request('GET', '/register');
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->userRepository = $this->entityManager->getRepository(User::class);
        //////////////////////////////

        $form = $crawler->selectButton('Register')->form();
        $form['registration_form[firstName]'] = 'Aocheng';
        $form['registration_form[lastName]'] = 'Zhao';
        $form['registration_form[email]'] = 'meaochengzhao@outlook.com';
        $form['registration_form[address]'] = 'Leuven';
        $form['registration_form[birthday][year]'] = '2001';
        $form['registration_form[birthday][month]'] = '6';
        $form['registration_form[birthday][day]'] = '14';
        $form['registration_form[plainPassword]'] = '123456';
        $form['registration_form[agreeTerms]']->tick();
        $email = $form['registration_form[email]']->getValue();

        $client->submit($form);
        // Validate the response or perform assertions using WebDriver methods
        $client->waitFor('div.alert.alert-success',5); // Wait for success flash message to appear
        $this->assertSelectorTextContains('header>div>h1','Login');
        $this->user = $this->userRepository->findOneByEmail($email);

    }
    protected function tearDown(): void
    {

//        $savedUser = $this->entityManager->getReference(User::class, $this->user->getId());
//        $user = new User();
//        $user->setEmail('12345@gmail.com');
//        $user->setPassword('Abc123456');
//        $user->setLastName('me');
//        $user->setFirstName('tester');
//        $birthday = new DateTime('1990-01-01'); // Replace with the desired birthday date
//        $user->setBirthday($birthday);
//        $user->setAddress('my address');
////        $this->entityManager->merge($user);
//        $this->entityManager->persist($user);
//
//        $this->entityManager->flush();
//        $savedUser = $this->userRepository->findOneByEmail('12345@gmail.com');
//        echo $savedUser->getEmail();
        /////////////////
//        $client->close();
//        self::stopWebServer();
        parent::tearDown();

        // Delete test data from the database
        // Retrieve the saved user from the database and remove
//        echo $this->user->getId();
//        echo $this->user->getEmail();
//        $userId = $this->user->getId();
////        $user = $this->entityManager->getReference(User::class, $this->user->getId());
////        $this->entityManager->merge($user);
//        $this->userRepository->remove($this->user);
//
//        $removedUser = $this->userRepository->find($userId);
//        $this->assertNull($removedUser);

//
//
//        // doing this is recommended to avoid memory leaks
//        $this->entityManager->close();
//        $this->entityManager = null;
    }

}
