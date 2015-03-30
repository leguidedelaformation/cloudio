<?php

namespace Cdo\SiteBundle\Tests\Controller\Visitor;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SiteControllerTest extends WebTestCase
{
    public function testNavbar()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/test-training/');
        
        $link = $crawler->filter('a:contains("Qui sommes-nous")')->eq(0)->link();
        $crawler = $client->click($link);
        $this->assertCount(1, $crawler->filter('h1:contains("Qui sommes-nous")'));
        
        $link = $crawler->filter('a:contains("Certifications")')->eq(0)->link();
        $crawler = $client->click($link);
        $this->assertCount(1, $crawler->filter('h1:contains("Certifications")'));
        
        $link = $crawler->filter('a:contains("Contact")')->eq(0)->link();
        $crawler = $client->click($link);
        $this->assertCount(1, $crawler->filter('h1:contains("Contact")'));
    }
}
