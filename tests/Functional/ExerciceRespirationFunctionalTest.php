<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExerciceRespirationFunctionalTest extends WebTestCase
{
    public function testPageExercicesAccessible(): void
    {
        $client = static::createClient();
        $client->request('GET', '/exercice/respiration');

        $this->assertResponseIsSuccessful();
    }

    public function testPageNouvelExerciceAccessibleSansConnexion(): void
    {
        $client = static::createClient();
        $client->request('GET', '/exercice/respiration/new');

        // Accessible sans connexion (sauvegarde en session)
        $this->assertResponseIsSuccessful();
    }

    public function testFormulairNouvelExerciceContientChamps(): void
    {
        $client = static::createClient();
        $client->request('GET', '/exercice/respiration/new');

        $this->assertSelectorExists('input[id*="nameSeries"], input[name*="nameSeries"]');
    }

    public function testExportJsonNecessiteConnexion(): void
    {
        $client = static::createClient();
        $client->request('GET', '/exercice/respiration/export/json');

        $this->assertResponseRedirects('/login');
    }

    public function testImportJsonNecessiteConnexion(): void
    {
        $client = static::createClient();
        $client->request('POST', '/exercice/respiration/import/json');

        $this->assertResponseRedirects('/login');
    }

    public function testExerciceInexistantRetourne404(): void
    {
        $client = static::createClient();
        $client->request('GET', '/exercice/respiration/99999');

        $this->assertResponseStatusCodeSame(404);
    }
}