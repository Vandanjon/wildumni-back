<?php

namespace App\DataFixtures;

use App\Entity\Address;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AddressFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $address1 = new Address();
        $address1->setCountry("France");
        $address1->setRegion("Pays de la Loire");
        $address1->setPostcode("44000");
        $address1->setCity("Nantes");
        $address1->setStreet("allée des pins");
        $address1->setStreetNumber(10);
        $address1->setLatitude(47.2138);
        $address1->setLongitude(-1.5762);


        $address2 = new Address();
        $address2->setCountry("France");
        $address2->setRegion("Pyrénées-Orientales");
        $address2->setPostcode("66000");
        $address2->setCity("Perpignan");
        $address2->setStreet("rue des pastèques");
        $address2->setStreetNumber(1);
        $address2->setLatitude(42.7104);
        $address2->setLongitude(2.8856);


        $manager->persist($address1);
        $manager->persist($address2);

        $this->addReference("address1", $address1);
        $this->addReference("address2", $address2);

        $manager->flush();
    }
}
