<?php

namespace App\DataFixtures;

use App\Entity\Valuation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ValuationFixtures extends Fixture
{   
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i <10; $i++) {
            $valuation = new Valuation();
            $valuation->setDate(new \DateTime('now'));
            $valuation->setDelta($i*3);
            $manager->persist($valuation);
        }
        $manager->flush();
    }
}