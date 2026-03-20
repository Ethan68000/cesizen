<?php

namespace App\DataFixtures;

use App\Entity\Informations;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class InformationsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $informations = [
            [
                'title' => 'Qu\'est-ce que le stress ?',
                'slug' => 'quest-ce-que-le-stress',
                'description' => 'Le stress est une réaction naturelle de l\'organisme face à une situation perçue comme menaçante ou difficile. Il peut être positif (eustress) ou négatif (distress) selon son intensité et sa durée.',
            ],
            [
                'title' => 'Les effets du stress sur la santé',
                'slug' => 'effets-stress-sante',
                'description' => 'Un stress chronique peut avoir des effets négatifs sur la santé : troubles du sommeil, maux de tête, problèmes digestifs, anxiété et dépression. Il est important de le gérer efficacement.',
            ],
            [
                'title' => 'La cohérence cardiaque',
                'slug' => 'coherence-cardiaque',
                'description' => 'La cohérence cardiaque est une technique de respiration qui permet de réduire le stress et d\'améliorer le bien-être. Elle consiste à respirer à un rythme régulier de 6 cycles par minute.',
            ],
            [
                'title' => 'Les bienfaits de la méditation',
                'slug' => 'bienfaits-meditation',
                'description' => 'La méditation permet de réduire le stress, améliorer la concentration et favoriser le bien-être général. Pratiquer 10 minutes par jour peut avoir des effets significatifs sur la santé mentale.',
            ],
            [
                'title' => 'Bien dormir pour mieux gérer le stress',
                'slug' => 'bien-dormir-stress',
                'description' => 'Le sommeil est essentiel pour récupérer et gérer le stress. Il est recommandé de dormir entre 7 et 9 heures par nuit et d\'adopter une routine de coucher régulière.',
            ],
        ];

        foreach ($informations as $data) {
            $info = new Informations();
            $info->setTitle($data['title']);
            $info->setSlug($data['slug']);
            $info->setDescription($data['description']);
            $info->setCreationDate(new \DateTime());
            $manager->persist($info);
        }

        $manager->flush();
    }
}