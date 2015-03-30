<?php

namespace Cdo\BlogBundle\Tests\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PageControllerTest extends WebTestCase
{
//    public function testIndex()
//    {
//        $client = static::createClient();
//
//        $crawler = $client->request('GET', '/login');
//        $form = $crawler->selectButton('Connexion')->form(array('_username' => 'test', '_password' => 'test'));
//        $client->submit($form);
//        $crawler = $client->followRedirect();
//        $link = $crawler->filter('a:contains("Pages")')->eq(0)->link();
//        $crawler = $client->click($link);
//        
//        $this->assertTrue($crawler->filter('h1:contains("Pages")')->count() > 0);
//    }
    
    public function testCreate()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Connexion')->form(array('_username' => 'test', '_password' => 'test'));
        $client->submit($form);
        $crawler = $client->followRedirect();
        
        $link = $crawler->filter('a:contains("Pages")')->eq(0)->link();
        $crawler = $client->click($link);

        $link = $crawler->filter('a:contains("Ajouter")')->eq(0)->link();
        $crawler = $client->click($link);
        
        $this->assertTrue($crawler->filter('h1:contains("Ajouter")')->count() > 0);
        
        $form = $crawler->selectButton('Enregistrer')->form();
        $form['cdo_blogbundle_admin_page_createtype[title]'] = 'Ceci est une page de test';
//        $form['cdo_blogbundle_admin_page_createtype[parent]']->select('Qui sommes-nous');
        $form['cdo_blogbundle_admin_page_createtype[content]'] = '<p>Bonjour à tous.</p><p>Cette page a été générée par un test fonctionnel.</p>';
        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();
        
        $this->assertTrue($crawler->filter('a:contains("Ceci est une page de test")')->count() > 0);
        
        $crawler = $client->request('GET', '/test-training/ceci-est-une-page-de-test');
        
        $this->assertTrue($crawler->filter('h1:contains("Ceci est une page de test")')->count() > 0);
    }
}
