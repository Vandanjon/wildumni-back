<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_FR");

        $user = new User();
        $user->setEmail($faker->email());
        $password = $this->hasher->hashPassword($user, "password");
        $user->setPassword($password);
        $user->setFirstName($faker->firstName());
        $user->setLastName($faker->lastName());
        $user->setUserName("toto");
        $user->setAddress($this->getReference("address1"));
        $user->addSession($this->getReference("sesRemFr"));
        $user->addLanguage($this->getReference("language1"));
        $user->addLanguage($this->getReference("language2"));
        $user->addContactLink($this->getReference("linkSet1"));

        $admin = new User();
        $admin->setEmail("toto@tata.com");
        $admin->setRoles(["ROLE_ADMIN"]);
        $password = $this->hasher->hashPassword($admin, "password");
        $admin->setPassword($password);
        $admin->setFirstName($faker->firstName());
        $admin->setLastName($faker->lastName());
        $admin->setUserName("jojo");
        $admin->addSession($this->getReference("sesRemEn"));
        $admin->addLanguage($this->getReference("language3"));
        $admin->setAddress($this->getReference("address2"));
        $admin->addContactLink($this->getReference("linkSet2"));



        $manager->persist($user);
        $manager->persist($admin);

        $this->addReference("user", $user);
        $this->addReference("admin", $admin);


        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            AddressFixtures::class,
            LanguageFixtures::class,
            SessionFixtures::class,
            ContactLinkFixtures::class,
        ];
    }
}
