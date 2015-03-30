<?php

namespace Ptm\SiteBundle\Tests\Controller\Visitor;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SiteControllerTest extends WebTestCase
{
    public function testHomepage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertCount(1, $crawler->filter('h1:contains("Bienvenue")'));
    }
}
