<?php

namespace Cdo\BlogBundle\Tests\Controller\Visitor;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PageControllerTest extends WebTestCase
{
    public function testHomepage()
    {
        $client = static::createClient();

        // Redirect to homepage
        $crawler = $client->request('GET', '/test-training/');

        // Check if homepage is displayed
        $this->assertCount(1, $crawler->filter('h1:contains("Accueil")'));
        $this->assertCount(1, $crawler->filter('html:contains("Bienvenue sur le site de Test Training")'));
    }
    
    public function testShow()
    {
        $client = static::createClient();

        // Redirect to page
        $crawler = $client->request('GET', '/test-training/qui-sommes-nous');

        // Check if page is displayed
        $this->assertCount(1, $crawler->filter('h1:contains("Qui sommes-nous")'));
        $this->assertCount(1, $crawler->filter('html:contains("Nous sommes un organisme de formation fictif.")'));
    }
}
