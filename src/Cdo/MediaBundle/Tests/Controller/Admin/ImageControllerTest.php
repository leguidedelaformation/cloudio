<?php

namespace Cdo\MediaBundle\Tests\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageControllerTest extends WebTestCase
{
    public function testIndex()
    {
        // Login as 'test'
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Connexion')->form(array('_username' => 'test', '_password' => 'test'));
        $client->submit($form);
        $crawler = $client->followRedirect();
        
        // Redirect to image index
        $link = $crawler->filter('a:contains("Images")')->eq(0)->link();
        $crawler = $client->click($link);
        
        $this->assertTrue($crawler->filter('h1:contains("Images")')->count() > 0);
    }
    
    public function testCreate()
    {
        // Login as 'test'
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Connexion')->form(array('_username' => 'test', '_password' => 'test'));
        $client->submit($form);
        $crawler = $client->followRedirect();
        
        // Redirect to image index
        $link = $crawler->filter('a:contains("Images")')->eq(0)->link();
        $crawler = $client->click($link);

        // Redirect to image upload
        $link = $crawler->filter('a:contains("Ajouter")')->eq(0)->link();
        $crawler = $client->click($link);
        
        // Check if image upload form is displayed
        $this->assertTrue($crawler->filter('h1:contains("Ajouter une image")')->count() > 0);
        
        // Fill & submit image upload form
        $file = new UploadedFile(
            dirname(__FILE__).'/image_test.png',
            'image_test.png',
            'image/png',
            26360
        );
        $form = $crawler->selectButton('Enregistrer')->form();
        $form['cdo_mediabundle_account_image_uploadtype[file]']->upload($file);
        $form['cdo_mediabundle_account_image_uploadtype[alt]'] = 'Image de test';
        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();
        
        // Check if uploaded image is displayed in image index
        $this->assertTrue($crawler->filter('html:contains("Image de test")')->count() > 0);
    }
    
    public function testUpdate()
    {
        // Login as 'test'
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Connexion')->form(array('_username' => 'test', '_password' => 'test'));
        $client->submit($form);
        $crawler = $client->followRedirect();
        
        // Redirect to image index
        $link = $crawler->filter('a:contains("Images")')->eq(0)->link();
        $crawler = $client->click($link);

        // Redirect to edition of newly uploaded image
        $link = $crawler->filterXpath('//div[@id="image-index"]/div/div/div[contains(text(), "Image de test")]/../following-sibling::div[1]/a[@aria-label="Editer"]')->eq(0)->link();
        $crawler = $client->click($link);
        
        // Check if image update form is displayed
        $this->assertTrue($crawler->filter('h1:contains("Image de test")')->count() > 0);
        
        // Fill & submit page update form
        $form = $crawler->selectButton('Mettre à jour')->form();
        $form['cdo_mediabundle_account_image_updatetype[alt]'] = 'Image à jour';
        $form['cdo_mediabundle_account_image_updatetype[navbarlogo]']->tick();
        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();
        
        // Check if page data was updated in page index
        $this->assertTrue($crawler->filter('html:contains("Image à jour (Logo)")')->count() > 0);
        
        // Redirect to homepage in frontend
        $crawler = $client->request('GET', '/test-training/');
        
        // Check if updated image is displayed as logo
        $this->assertTrue($crawler->filterXpath('//a[@class="navbar-brand"]/img[@alt="Image à jour"]')->count() > 0);
    }
    
    public function testRemove()
    {
        // Login as 'test'
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Connexion')->form(array('_username' => 'test', '_password' => 'test'));
        $client->submit($form);
        $crawler = $client->followRedirect();
        
        // Redirect to image index
        $link = $crawler->filter('a:contains("Images")')->eq(0)->link();
        $crawler = $client->click($link);

        // Redirect to removal of newly updated image
        $link = $crawler->filterXpath('//div[@id="image-index"]/div/div/div[contains(text(), "Image à jour (Logo)")]/../following-sibling::div[1]/a[@aria-label="Supprimer"]')->eq(0)->link();
        $crawler = $client->click($link);
        
        // Check if page removal form is displayed
        $this->assertTrue($crawler->filter('h1:contains("Image à jour")')->count() > 0);
        
        // Submit page removal form
        $form = $crawler->selectButton('Supprimer')->form();
        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();
        
        // Check if crawler was redirected to page index
        $this->assertTrue($crawler->filter('h1:contains("Images")')->count() > 0);
        
        // Check if page was removed from page index
        $this->assertTrue($crawler->filter('html:contains("Image à jour (Logo)")')->count() == 0);
    }
}
