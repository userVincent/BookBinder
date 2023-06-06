<?php

namespace App\Tests\unit\Repository;

use DateTime;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Date;


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


        //unsupported user test

        $unsupportedUser = new unsupportedUser();
        $this->expectException(UnsupportedUserException::class);
        //$this->expectExceptionMessage(sprintf('Instances of "%s" are not supported.', \get_class($unsupportedUser)));

        $this->userRepository->upgradePassword($unsupportedUser, 'hashed password');

//        ////////////////
//        // Create a mock object of PasswordAuthenticatedUserInterface
//        $userMock = $this->createMock(PasswordAuthenticatedUserInterface::class);
//
//        // Expect the UnsupportedUserException to be thrown
//        $this->expectException(UnsupportedUserException::class);
//
//        // Call the upgradePassword() method with the mock object
//        $this->userRepository->upgradePassword($userMock, 'NewPassword123');
    }

    public function testFindOneByEmail(){
        $this->userRepository->save($this->user, true);

        // Retrieve the updated user from the database
        // find by email
        $foundUser = $this->userRepository->findOneByEmail($this->user->getEmail());

        // Assert that the user is found correctly
        $this->assertEquals($this->user, $foundUser);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}

class unsupportedUser implements UserInterface,PasswordAuthenticatedUserInterface
{

    public function getRoles(): array
    {
        // TODO: Implement getRoles() method.
        return [];
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        // TODO: Implement getUserIdentifier() method.
        return '';
    }

    public function getPassword(): ?string
    {
        // TODO: Implement getPassword() method.
        return '';
    }
}