<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public const STRESS_REF  = 'category-stress';
    public const VIE_REF     = 'category-vie';
    public const Nature_REF   = 'category-nature';

    public function load(ObjectManager $manager): void
    {
        $categories = [
            self::STRESS_REF => 'Stress',
            self::VIE_REF    => 'Vie',
            self::Nature_REF  => 'Nature',
        ];

        foreach ($categories as $ref => $name) {
            $category = new Category();
            $category->setName($name);
            $manager->persist($category);
            $this->addReference($ref, $category);
        }

        $manager->flush();
    }
}