<?php

namespace Cdo\UserBundle\Tests\Controller\User;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProfileControllerTest extends WebTestCase
{
    public function testShow()
    {
        // Login as 'test'
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Connexion')->form(array('_username' => 'test', '_password' => 'test'));
        $client->submit($form);
        $crawler = $client->followRedirect();
        
        // Redirect to profile show
        $link = $crawler->filter('a:contains("Profil")')->eq(0)->link();
        $crawler = $client->click($link);
        
        // Check if profile is displayed
        $this->assertTrue($crawler->filter('h1:contains("Utilisateur")')->count() > 0);
    }
    
    public function testEdit()
    {
        // Login as 'test'
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Connexion')->form(array('_username' => 'test', '_password' => 'test'));
        $client->submit($form);
        $crawler = $client->followRedirect();
        
        // Redirect to profile show
        $link = $crawler->filter('a:contains("Profil")')->eq(0)->link();
        $crawler = $client->click($link);
        
        // Redirect to profile edit
        $link = $crawler->filter('a:contains("Editer")')->eq(0)->link();
        $crawler = $client->click($link);
        
        // Check if profile edit form is displayed
        $this->assertTrue($crawler->filter('h2:contains("Edition du profil")')->count() > 0);
        
        // Fill & submit profile edit form
        $form = $crawler->selectButton('Mettre à jour')->form();
        $form['cdo_user_profile[lastname]'] = 'Dylan';
        $form['cdo_user_profile[firstname]'] = 'Bob';
        $form['cdo_user_profile[email]'] = 'hello@test.com';
        $form['cdo_user_profile[phone]'] = '0987654321';
        $form['cdo_user_profile[current_password]'] = 'test';
        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();
        
        // Check if profile data was updated in profile show
        $this->assertTrue($crawler->filter('h2:contains("Profil")')->count() > 0);
        $this->assertTrue($crawler->filter('html:contains("Dylan")')->count() > 0);
        $this->assertTrue($crawler->filter('html:contains("Bob")')->count() > 0);
        $this->assertTrue($crawler->filter('html:contains("hello@test.com")')->count() > 0);
        $this->assertTrue($crawler->filter('html:contains("0987654321")')->count() > 0);
        
        // Redirect to profile edit
        $link = $crawler->filter('a:contains("Editer")')->eq(0)->link();
        $crawler = $client->click($link);
        
        // Restore default profile data
        $form = $crawler->selectButton('Mettre à jour')->form();
        $form['cdo_user_profile[lastname]'] = 'Doe';
        $form['cdo_user_profile[firstname]'] = 'John';
        $form['cdo_user_profile[email]'] = 'test@test.com';
        $form['cdo_user_profile[phone]'] = '0123456789';
        $form['cdo_user_profile[current_password]'] = 'test';
        $crawler = $client->submit($form);
    }
}
