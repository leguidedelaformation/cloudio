<?php

namespace Cdo\AccountBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Cdo\UserBundle\Entity\User;

class LoadUser extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;
    
    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $entityManager = $this->container->get('doctrine')->getManager();
        $userManager = $this->container->get('fos_user.user_manager');
        $date = new \Datetime('now');
        
        $user_array = array(
            // Le 1er utilisateur est l'administrateur
            array(
                'username' => 'admin',
                'email'    => 'admin@admin.com',
                'password' => 'admin',
            ),
            // Le 2e utilisateur est le compte de test
            array(
                'username' => 'test',
                'email'    => 'test@test.com',
                'password' => 'test',
            ),
            // Le 3e utilisateur est le compte de démonstration
            array(
                'username' => 'demo',
                'email'    => 'demo@demo.com',
                'password' => 'demo',
            ),
        );
    
        $i = 0;
        foreach ($user_array as $user_data)
        {
            $account = $entityManager->getRepository('CdoAccountBundle:Account')
                                     ->getNumbered($i);
                
            if ($account) {
                $user = $userManager->createUser();
                $user->setCreatedAt($date);
                $user->setUpdatedAt($date);
                $user->setAccount($account);
                if ($user_data['username'] == 'admin')
                {
                    $user->addRole('ROLE_ADMIN');
                } else {
                    $user->addRole('ROLE_ACCOUNT');
                }
                $user->setUsername($user_data['username']);
                $user->setEmail($user_data['email']);
                $user->setPlainPassword($user_data['password']);
                $user->setEnabled(true);
                $userManager->updateUser($user);
          
                $manager->persist($user);
            }
            $i++;
        }
    
        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
}