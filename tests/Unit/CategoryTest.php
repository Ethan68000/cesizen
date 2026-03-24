<?php

namespace App\Tests\Unit;

use App\Entity\Category;
use App\Entity\Informations;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    public function testGetSetName(): void
    {
        $category = new Category();
        $category->setName('Stress');

        $this->assertSame('Stress', $category->getName());
    }

    public function testToString(): void
    {
        $category = new Category();
        $category->setName('Santé');

        $this->assertSame('Santé', (string) $category);
    }

    public function testIdEstNullParDefaut(): void
    {
        $category = new Category();

        $this->assertNull($category->getId());
    }

    public function testCollectionInformationsVideeParDefaut(): void
    {
        $category = new Category();

        $this->assertCount(0, $category->getInformations());
    }

    public function testAddInformation(): void
    {
        $category    = new Category();
        $information = new Informations();

        $category->addInformation($information);

        $this->assertCount(1, $category->getInformations());
        $this->assertTrue($category->getInformations()->contains($information));
    }

    public function testAddInformationDoublonIgnore(): void
    {
        $category    = new Category();
        $information = new Informations();

        $category->addInformation($information);
        $category->addInformation($information);

        $this->assertCount(1, $category->getInformations());
    }

    public function testRemoveInformation(): void
    {
        $category    = new Category();
        $information = new Informations();

        $category->addInformation($information);
        $category->removeInformation($information);

        $this->assertCount(0, $category->getInformations());
    }
}