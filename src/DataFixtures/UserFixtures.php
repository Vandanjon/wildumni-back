<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(UserPasswordHasherInterface $passwordHasher) 
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail("user@test.com");
        $user->setFirstName("userFirstName");
        $hashedPassword1 =$this->passwordHasher->hashPassword($user, "password");
        $user->setPassword($hashedPassword1);

        $trainer = new User();
        $trainer->setEmail("trainer@test.com");
        $trainer->setFirstName("trainerFirstName");
        $hashedPassword2 =$this->passwordHasher->hashPassword($trainer, "password");
        $trainer->setPassword($hashedPassword2);

        $admin = new User();
        $admin->setEmail("admin@test.com");
        $admin->setFirstName("adminFirstName");
        $hashedPassword3 =$this->passwordHasher->hashPassword($admin, "password");
        $admin->setPassword($hashedPassword3);


        $manager->persist($user);
        $manager->persist($trainer);
        $manager->persist($admin);
        
        $manager->flush();
    }
}
