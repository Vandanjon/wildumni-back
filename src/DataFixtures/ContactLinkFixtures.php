<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\ContactLink;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ContactLinkFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_FR");

        $linkSet1 = new ContactLink();
        $linkSet1->setGithub($faker->lexify('https://github.com/???????'));
        $linkSet1->setLinkedin($faker->lexify('https://www.linkedin.com/in/?????????'));

        $linkSet2 = new ContactLink();
        $linkSet2->setGithub($faker->lexify('https://github.com/???????'));
        $linkSet2->setLinkedin($faker->lexify('https://www.linkedin.com/in/?????????'));

        $manager->persist($linkSet1);
        $manager->persist($linkSet2);


        $this->addReference("linkSet1", $linkSet1);
        $this->addReference("linkSet2", $linkSet2);

        $manager->flush();
    }
}
