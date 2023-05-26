<?php

namespace App\Tests\unit\Repository;

use DateTime;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;


class UserRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;
    private $user;
    private $userRepository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->userRepository = $this->entityManager->getRepository(User::class);
        $this->user = new User();
        $this->user->setEmail('12345@gmail.com');
        $this->user->setPassword('Abc123456');
        $this->user->setLastName('me');
        $this->user->setFirstName('tester');
        $birthday = new DateTime('1990-01-01'); // Replace with the desired birthday date
        $this->user->setBirthday($birthday);
        $this->user->setAddress('my address');
    }

    public function testSave()
    {
        $this->userRepository->save($this->user, true);

        // Retrieve the saved user from the database
        $savedUser = $this->userRepository->find($this->user->getId());

        $this->assertEquals($this->user->getEmail(), $savedUser->getEmail());
        $this->assertEquals($this->user->getFirstName(), $savedUser->getFirstName());
        $this->assertEquals($this->user->getLastName(), $savedUser->getLastName());
        $this->assertEquals($this->user->getBirthday(), $savedUser->getBirthday());
        $this->assertEquals($this->user->getPassword(), $savedUser->getPassword());
    }

    public function testRemove()
    {
        $this->userRepository->save($this->user, true);
        $userId = $this->user->getId();//first retrieve the userId before removing
        $this->userRepository->remove($this->user, true);
        $removedUser = $this->userRepository->find($userId);
        $this->assertNull($removedUser);
    }

    public function testUpgradePassword()
    {
        $this->userRepository->save($this->user, true);

        // Set the new password
        $newPassword = 'NewPassword123';
        $this->userRepository->upgradePassword($this->user, $newPassword);

        // Retrieve the updated user from the database
        $updatedUser = $this->userRepository->find($this->user->getId());

        // Assert that the password has been updated
        $this->assertEquals($newPassword, $updatedUser->getPassword());

        ////////////////
        // Create a mock object of PasswordAuthenticatedUserInterface
        $userMock = $this->createMock(PasswordAuthenticatedUserInterface::class);

        // Expect the UnsupportedUserException to be thrown
        $this->expectException(UnsupportedUserException::class);

        // Call the upgradePassword() method with the mock object
        $this->userRepository->upgradePassword($userMock, 'NewPassword123');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}