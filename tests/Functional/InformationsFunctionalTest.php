<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class InformationsFunctionalTest extends WebTestCase
{
    public function testPageInformationsAccessible(): void
    {
        $client = static::createClient();
        $client->request('GET', '/informations');

        $this->assertResponseIsSuccessful();
    }

    public function testPageInformationsContientTitre(): void
    {
        $client = static::createClient();
        $client->request('GET', '/informations');

        $this->assertSelectorTextContains('h1', 'Informations');
    }

    public function testFiltreParCategorieRetourneSucces(): void
    {
        $client = static::createClient();
        $client->request('GET', '/informations?category=1');

        $this->assertResponseIsSuccessful();
    }

    public function testFiltreParCategorieInexistanteRetourneSucces(): void
    {
        $client = static::createClient();
        $client->request('GET', '/informations?category=9999');

        $this->assertResponseIsSuccessful();
    }

    public function testCreerInformationNecessiteConnexion(): void
    {
        $client = static::createClient();
        $client->request('GET', '/informations/new');

        $this->assertResponseRedirects('/login');
    }

    public function testModifierInformationNecessiteConnexion(): void
    {
        $client = static::createClient();
        $client->request('GET', '/informations/1/edit');

        $this->assertResponseRedirects('/login');
    }
}