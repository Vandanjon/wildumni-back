<?php

namespace App\DataFixtures;

use App\Entity\Language;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LanguageFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $language1 = new Language();
        $language2 = new Language();
        $language3 = new Language();

        $language1->setName("PHP");
        $language2->setName("JavaScript");
        $language3->setName("Python");

        $manager->persist($language1);
        $manager->persist($language2);
        $manager->persist($language3);
        $manager->flush();
    }
}
