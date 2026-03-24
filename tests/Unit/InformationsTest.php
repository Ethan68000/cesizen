<?php

namespace App\Tests\Unit;

use App\Entity\Category;
use App\Entity\Informations;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class InformationsTest extends TestCase
{
    public function testGetSetTitle(): void
    {
        $info = new Informations();
        $info->setTitle('Mon titre');

        $this->assertSame('Mon titre', $info->getTitle());
    }

    public function testGetSetSlug(): void
    {
        $info = new Informations();
        $info->setSlug('mon-titre');

        $this->assertSame('mon-titre', $info->getSlug());
    }

    public function testGetSetDescription(): void
    {
        $info = new Informations();
        $info->setDescription('Une description.');

        $this->assertSame('Une description.', $info->getDescription());
    }

    public function testGetSetCreationDate(): void
    {
        $info = new Informations();
        $date = new \DateTime('2025-01-01');
        $info->setCreationDate($date);

        $this->assertSame($date, $info->getCreationDate());
    }

    public function testIdEstNullParDefaut(): void
    {
        $info = new Informations();

        $this->assertNull($info->getId());
    }

    public function testCollectionCategoriesVideeParDefaut(): void
    {
        $info = new Informations();

        $this->assertCount(0, $info->getCategories());
    }

    public function testAddCategory(): void
    {
        $info     = new Informations();
        $category = new Category();
        $category->setName('Stress');

        $info->addCategory($category);

        $this->assertCount(1, $info->getCategories());
        $this->assertTrue($info->getCategories()->contains($category));
    }

    public function testAddCategoryDoublonIgnore(): void
    {
        $info     = new Informations();
        $category = new Category();

        $info->addCategory($category);
        $info->addCategory($category);

        $this->assertCount(1, $info->getCategories());
    }

    public function testRemoveCategory(): void
    {
        $info     = new Informations();
        $category = new Category();

        $info->addCategory($category);
        $info->removeCategory($category);

        $this->assertCount(0, $info->getCategories());
    }

    public function testGetSetAdmin(): void
    {
        $info  = new Informations();
        $admin = new User();

        $info->setAdmin($admin);

        $this->assertSame($admin, $info->getAdmin());
    }

    public function testAdminNullParDefaut(): void
    {
        $info = new Informations();

        $this->assertNull($info->getAdmin());
    }

    public function testSetAdminNull(): void
    {
        $info  = new Informations();
        $admin = new User();

        $info->setAdmin($admin);
        $info->setAdmin(null);

        $this->assertNull($info->getAdmin());
    }
}