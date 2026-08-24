<?php

namespace App\Tests\Functional;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class XssProfileTest extends WebTestCase
{
    public function test_pseudo_with_script_tag_is_escaped_on_display(): void
    {
        $client = static::createClient();

        /** @var UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);
        $admin = $userRepository->findOneBy(['email' => 'admin@gmail.com']);

        $this->assertNotNull($admin, 'Admin fixture introuvable, lance doctrine:fixtures:load --env=test');

        $client->loginUser($admin);

        $xssPayload = '<script>alert(1)</script>';

        $crawler = $client->request('GET', '/user/' . $admin->getId() . '/edit');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Enregistrer les modifications')->form([
            'user[pseudo]' => $xssPayload,
        ]);

        $client->submit($form);
        $this->assertResponseRedirects();
        $client->followRedirect();

        // On va consulter la fiche utilisateur pour vérifier l'affichage du pseudo
        $client->request('GET', '/user/' . $admin->getId());
        $this->assertResponseIsSuccessful();

        $content = $client->getResponse()->getContent();

        // Le script ne doit JAMAIS apparaître tel quel dans le HTML
        $this->assertStringNotContainsString($xssPayload, $content);

        // Twig doit avoir échappé le contenu (entités HTML)
        $this->assertStringContainsString('&lt;script&gt;', $content);

        // Aucune balise <script> injectée ne doit exister dans le DOM
        $this->assertSelectorNotExists('script:contains("alert(1)")');
    }
}