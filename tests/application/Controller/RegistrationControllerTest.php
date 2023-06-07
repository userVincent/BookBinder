<?php

namespace App\Tests\application\Controller;

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

    public function testRegister():void
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

//        $form['registration_form[firstName]'] = 'Aocheng';
//        $form['registration_form[lastName]'] = 'Zhao';
//        $form['registration_form[email]'] = 'aocheng.zhao@student.kuleuven.be';
//        $form['registration_form[address]'] = 'Leuven';
//        $form['registration_form[birthday][year]'] = '2001';
//        $form['registration_form[birthday][month]'] = '6';
//        $form['registration_form[birthday][day]'] = '14';
//        $form['registration_form[plainPassword]'] = '123456';
//        $form['registration_form[agreeTerms]']->tick();

        $this->client->submit($form);
        // Follow the redirection
        $this->client->followRedirect();

        // Assert that the current page is the app_login route
        $this->assertRouteSame('app_login');
        $this->assertSelectorTextContains('header>div>h1','Login');
    }
}