<?php

namespace App\DataFixtures;

use App\Entity\Informations;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class InformationsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $informations = [
            [
                'title'       => 'Qu\'est-ce que le stress ?',
                'slug'        => 'quest-ce-que-le-stress',
                'description' => 'Le stress est une réaction naturelle de l\'organisme face à une situation perçue comme menaçante ou difficile. Il peut être positif (eustress) ou négatif (distress) selon son intensité et sa durée.',
                'categories'  => [CategoryFixtures::STRESS_REF],
            ],
            [
                'title'       => 'Les effets du stress sur la santé',
                'slug'        => 'effets-stress-sante',
                'description' => 'Un stress chronique peut avoir des effets négatifs sur la santé : troubles du sommeil, maux de tête, problèmes digestifs, anxiété et dépression. Il est important de le gérer efficacement.',
                'categories'  => [CategoryFixtures::STRESS_REF, CategoryFixtures::Nature_REF],
            ],
            [
                'title'       => 'La cohérence cardiaque',
                'slug'        => 'coherence-cardiaque',
                'description' => 'La cohérence cardiaque est une technique de respiration qui permet de réduire le stress et d\'améliorer le bien-être. Elle consiste à respirer à un rythme régulier de 6 cycles par minute.',
                'categories'  => [CategoryFixtures::Nature_REF],
            ],
            [
                'title'       => 'Les bienfaits de la méditation',
                'slug'        => 'bienfaits-meditation',
                'description' => 'La méditation permet de réduire le stress, améliorer la concentration et favoriser le bien-être général. Pratiquer 10 minutes par jour peut avoir des effets significatifs sur la santé mentale.',
                'categories'  => [CategoryFixtures::VIE_REF, CategoryFixtures::Nature_REF],
            ],
            [
                'title'       => 'Bien dormir pour mieux gérer le stress',
                'slug'        => 'bien-dormir-stress',
                'description' => 'Le sommeil est essentiel pour récupérer et gérer le stress. Il est recommandé de dormir entre 7 et 9 heures par nuit et d\'adopter une routine de coucher régulière.',
                'categories'  => [CategoryFixtures::VIE_REF, CategoryFixtures::STRESS_REF],
            ],
        ];

        // Récupérer l'admin depuis les références UserFixtures
        $admin = $this->getReference(UserFixtures::ADMIN_REF, \App\Entity\User::class);

        foreach ($informations as $data) {
            $info = new Informations();
            $info->setTitle($data['title']);
            $info->setSlug($data['slug']);
            $info->setDescription($data['description']);
            $info->setCreationDate(new \DateTime());
            $info->setAdmin($admin);

            foreach ($data['categories'] as $categoryRef) {
                $info->addCategory($this->getReference($categoryRef, \App\Entity\Category::class));
            }

            $manager->persist($info);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            UserFixtures::class,
        ];
    }
}