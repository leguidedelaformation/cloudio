<?php

namespace Cdo\UserBundle\Tests\Controller\User;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ChangePasswordControllerTest extends WebTestCase
{
    public function testChangePassword()
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
        
        // Redirect to password change
        $link = $crawler->filter('a:contains("Changer de mot de passe")')->eq(0)->link();
        $crawler = $client->click($link);
        
        // Check if profile edit form is displayed
        $this->assertTrue($crawler->filter('h2:contains("Changement de mot de passe")')->count() > 0);
        
        // Fill & submit profile edit form
        $form = $crawler->selectButton('Modifier le mot de passe')->form();
        $form['fos_user_change_password_form[current_password]'] = 'test';
        $form['fos_user_change_password_form[plainPassword][first]'] = 'pas$w0rd';
        $form['fos_user_change_password_form[plainPassword][second]'] = 'pas$w0rd';
        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();
        
        // Check if crawler is redirected to profile show
        $this->assertTrue($crawler->filter('h2:contains("Profil")')->count() > 0);
        
        // Logout
        $link = $crawler->filter('a:contains("Déconnexion")')->eq(0)->link();
        $crawler = $client->click($link);
        
        // Login with new password
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Connexion')->form(array('_username' => 'test', '_password' => 'pas$w0rd'));
        $client->submit($form);
        $crawler = $client->followRedirect();
        
        // Check if crawler is redirected to admin dashboard
        $this->assertTrue($crawler->filter('h1:contains("Tableau de bord")')->count() > 0);
        
        // Restore default password
        $link = $crawler->filter('a:contains("Profil")')->eq(0)->link();
        $crawler = $client->click($link);
        $link = $crawler->filter('a:contains("Changer de mot de passe")')->eq(0)->link();
        $crawler = $client->click($link);
        $form = $crawler->selectButton('Modifier le mot de passe')->form();
        $form['fos_user_change_password_form[current_password]'] = 'pas$w0rd';
        $form['fos_user_change_password_form[plainPassword][first]'] = 'test';
        $form['fos_user_change_password_form[plainPassword][second]'] = 'test';
        $crawler = $client->submit($form);
    }
}
