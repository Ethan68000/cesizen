<?php

namespace App\DataFixtures;

use App\Entity\ExerciceRespiration;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ExercicePredefiniFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $exercices = [
            ['7-4-8', 7, 4, 8],
            ['5-5 Cohérence cardiaque', 5, 0, 5],
            ['4-6', 4, 0, 6],
        ];

        foreach ($exercices as [$nom, $inspiration, $apnee, $expiration]) {
            $exercice = new ExerciceRespiration();
            $exercice->setNameSeries($nom);
            $exercice->setTimeInspiration($inspiration);
            $exercice->setTimeApnea($apnee);
            $exercice->setTimeExpiration($expiration);
            $manager->persist($exercice);
        }

        $manager->flush();
    }
}