<?php

namespace Cdo\BlogBundle\Tests\Controller\Visitor;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PageControllerTest extends WebTestCase
{
    public function testHomepage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/test-training/');

        $this->assertCount(1, $crawler->filter('h1:contains("Accueil")'));
        $this->assertCount(1, $crawler->filter('html:contains("Bienvenue sur le site de Test Training")'));
    }
    
    public function testShow()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/test-training/qui-sommes-nous');

        $this->assertCount(1, $crawler->filter('h1:contains("Qui sommes-nous")'));
        $this->assertCount(1, $crawler->filter('html:contains("Nous sommes un organisme de formation fictif.")'));
    }
}
