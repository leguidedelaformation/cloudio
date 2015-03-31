<?php

namespace Cdo\BlogBundle\Tests\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PageControllerTest extends WebTestCase
{
    public function testIndex()
    {
        // Login as 'test'
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Connexion')->form(array('_username' => 'test', '_password' => 'test'));
        $client->submit($form);
        $crawler = $client->followRedirect();
        
        // Redirect to page index
        $link = $crawler->filter('a:contains("Pages")')->eq(0)->link();
        $crawler = $client->click($link);
        
        // Check if page index is displayed
        $this->assertTrue($crawler->filter('h1:contains("Pages")')->count() > 0);
    }
    
    public function testCreate()
    {
        // Login as 'test'
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Connexion')->form(array('_username' => 'test', '_password' => 'test'));
        $client->submit($form);
        $crawler = $client->followRedirect();
        
        // Redirect to page index
        $link = $crawler->filter('a:contains("Pages")')->eq(0)->link();
        $crawler = $client->click($link);

        // Redirect to page create
        $link = $crawler->filter('a:contains("Ajouter")')->eq(0)->link();
        $crawler = $client->click($link);
        
        // Check if page creation form is displayed
        $this->assertTrue($crawler->filter('h1:contains("Ajouter une page")')->count() > 0);
        
        // Fill & submit page creation form
        $form = $crawler->selectButton('Enregistrer')->form();
        $form['cdo_blogbundle_admin_page_createtype[title]'] = 'Ceci est une page de test';
        $value = $crawler->filter('#cdo_blogbundle_admin_page_createtype_parent option:contains("Qui sommes-nous")')->attr('value');
        $form['cdo_blogbundle_admin_page_createtype[parent]']->select($value);
        $form['cdo_blogbundle_admin_page_createtype[content]'] = '<p>Bonjour à tous.</p><p>Cette page a été générée par un test fonctionnel.</p>';
        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();
        
        // Check if created page is displayed in page index
        $this->assertTrue($crawler->filter('a:contains("Ceci est une page de test")')->count() > 0);
        
        // Redirect to page view in frontend
        $crawler = $client->request('GET', '/test-training/ceci-est-une-page-de-test');
        
        // Check if created page is displayed in frontend
        $this->assertTrue($crawler->filter('h1:contains("Ceci est une page de test")')->count() > 0);
    }
    
    public function testUpdate()
    {
        // Login as 'test'
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Connexion')->form(array('_username' => 'test', '_password' => 'test'));
        $client->submit($form);
        $crawler = $client->followRedirect();
        
        // Redirect to page index
        $link = $crawler->filter('a:contains("Pages")')->eq(0)->link();
        $crawler = $client->click($link);
        
        // Redirect to edition of newly created page
        $link = $crawler->filterXpath('//td/a/span[contains(text(), "Ceci est une page de test")]/../../following-sibling::td[4]/a')->eq(0)->link();
        $crawler = $client->click($link);
        
        // Check if page update form is displayed
        $this->assertTrue($crawler->filter('h1:contains("Ceci est une page de test")')->count() > 0);
        
        // Fill & submit page update form
        $form = $crawler->selectButton('Mettre à jour')->form();
        $form['cdo_blogbundle_admin_page_updatetype[title]'] = 'Page de test à jour';
        $value = $crawler->filter('#cdo_blogbundle_admin_page_updatetype_parent option:contains("Certifications")')->attr('value');
        $form['cdo_blogbundle_admin_page_updatetype[parent]']->select($value);
        $form['cdo_blogbundle_admin_page_updatetype[content]'] = '<p>Bonjour à nouveau.</p><p>Cette page de test a été mise à jour.</p>';
        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();
        
        // Check if page data was updated in page index
        $this->assertTrue($crawler->filter('a:contains("Page de test à jour")')->count() > 0);
        
        // Redirect to page view in frontend
        $crawler = $client->request('GET', '/test-training/page-de-test-a-jour');
        
        // Check if created page is displayed in frontend
        $this->assertTrue($crawler->filter('h1:contains("Page de test à jour")')->count() > 0);
    }
    
    public function testRemove()
    {
        // Login as 'test'
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Connexion')->form(array('_username' => 'test', '_password' => 'test'));
        $client->submit($form);
        $crawler = $client->followRedirect();
        
        // Redirect to page index
        $link = $crawler->filter('a:contains("Pages")')->eq(0)->link();
        $crawler = $client->click($link);

        // Redirect to removal of newly updated page
        $link = $crawler->filterXpath('//td/a/span[contains(text(), "Page de test à jour")]/../../following-sibling::td[5]/a')->eq(0)->link();
        $crawler = $client->click($link);
        
        // Check if page removal form is displayed
        $this->assertTrue($crawler->filter('h1:contains("Page de test à jour")')->count() > 0);
        
        // Submit page removal form
        $form = $crawler->selectButton('Supprimer')->form();
        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();
        
        // Check if crawler was redirected to page index
        $this->assertTrue($crawler->filter('h1:contains("Pages")')->count() > 0);
        
        // Check if page was removed from page index
        $this->assertTrue($crawler->filter('a:contains("Page de test à jour")')->count() == 0);
    }
}
