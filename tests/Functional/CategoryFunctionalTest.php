<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryFunctionalTest extends WebTestCase
{
    public function testBackOfficecategoriesNecessiteConnexion(): void
    {
        $client = static::createClient();
        $client->request('GET', '/admin/category');

        // Redirige vers login si non connecté
        $this->assertResponseRedirects();
    }

    public function testBackOfficeCategoryNewNecessiteConnexion(): void
    {
        $client = static::createClient();
        $client->request('GET', '/admin/category/new');

        $this->assertResponseRedirects();
    }

    public function testFiltreInformationsParCategorieAfficheBoutons(): void
    {
        $client = static::createClient();
        $client->request('GET', '/informations');

        // La page doit contenir des boutons de filtre (Toutes + catégories)
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('a.btn');
    }
}