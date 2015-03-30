<?php

namespace Cdo\UserBundle\Tests\Controller\User;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RedirectControllerTest extends WebTestCase
{
    public function testLogin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Connexion')->form(array('_username' => 'admin', '_password' => 'admin'));
        $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertCount(1, $crawler->filter('h1:contains("Tableau de bord")'));
    }
}
