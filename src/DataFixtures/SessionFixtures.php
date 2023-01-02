<?php

namespace App\DataFixtures;

use App\Entity\Session;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SessionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $sesRemFr = new Session();
        $sesRemFr->setLocation("Remote FR");
        $sesRemFr->setStartDate(new DateTime("2022/03/01"));
        $sesRemFr->setEndDate(new DateTime("2022/07/30"));

        $sesRemEn = new Session();
        $sesRemEn->setLocation("Remote EN");
        $sesRemEn->setStartDate(new DateTime("2022/03/01"));
        $sesRemEn->setEndDate(new DateTime("2022/07/30"));

        $sesBiarritz = new Session();
        $sesBiarritz->setLocation("Biarritz");
        $sesBiarritz->setStartDate(new DateTime("2022/03/01"));
        $sesBiarritz->setEndDate(new DateTime("2022/07/30"));

        
        $manager->persist($sesRemFr);
        $manager->persist($sesRemEn);
        $manager->persist($sesBiarritz);

        $manager->flush();
    }
}
