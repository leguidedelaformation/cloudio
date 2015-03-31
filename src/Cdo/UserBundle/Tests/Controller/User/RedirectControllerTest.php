<?php

namespace Cdo\UserBundle\Tests\Controller\User;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RedirectControllerTest extends WebTestCase
{
    public function testLogin()
    {
        // Login as 'test'
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Connexion')->form(array('_username' => 'test', '_password' => 'test'));
        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check if admin dashboard is displayed
        $this->assertCount(1, $crawler->filter('h1:contains("Tableau de bord")'));
    }
}
