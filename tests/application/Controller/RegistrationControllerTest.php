<?php

namespace App\Tests\application\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    private $client;
    private $crawler;
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->crawler = $this->client->request('GET', '/register');
    }

    public function testResponse():void
    {
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('form', 'Email');
    }

    public function testRegisterAndVerifyEmail():void
    {
        $form = $this->crawler->selectButton('Register')->form();
        $form->setValues(['registration_form[firstName]'=>'Aocheng',
            'registration_form[lastName]'=>'Zhao',
            'registration_form[email]'=>'aochengzhao@student.kuleuven.be',
            'registration_form[address]'=>'Leuven',
            'registration_form[birthday][year]'=>'2001',
            'registration_form[birthday][month]'=>'6',
            'registration_form[birthday][day]'=>'14',
            'registration_form[plainPassword]'=>'123456',
            'registration_form[agreeTerms]'=> 1,
        ]);

        $this->client->submit($form);
        // Follow the redirection
        $this->crawler = $this->client->followRedirect();

        // Assert that the current page is the app_login route
        $this->assertRouteSame('app_login');
        $this->assertSelectorTextContains('header>div>h1','Login');

        $entityManager = $this->client->getContainer()->get(EntityManagerInterface::class);
        $userRepository = $entityManager->getRepository(User::class);

        // Retrieve the test user
        $testUser = $userRepository->findOneByEmail('aochengzhao@student.kuleuven.be');
        $form = $this->crawler->selectButton('Sign in')->form();
        $form->setValues([
            '_username' => 'aochengzhao@student.kuleuven.be' ,
            '_password' => '123456',
        ]);

        $this->client->submit($form);
        //test route verify_email
        $this->crawler = $this->client->request('GET', '/verify/email');

    }
}