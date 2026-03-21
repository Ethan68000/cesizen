<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        // Admin
        $admin = new User();
        $admin->setPseudo('admin');
        $admin->setEmail('admin@gmail.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setCreationDate(new \DateTime());
        $admin->setIsActive(true);
        $admin->setPassword($this->hasher->hashPassword($admin, 'AdminAdmin12+'));
        $manager->persist($admin);

        // User simple
        $user = new User();
        $user->setPseudo('ethan');
        $user->setEmail('ethan@gmail.com');
        $user->setRoles(['ROLE_USER']);
        $user->setCreationDate(new \DateTime());
        $user->setIsActive(true);
        $user->setPassword($this->hasher->hashPassword($user, 'UserEthan12++'));
        $manager->persist($user);

        $manager->flush();
    }
}