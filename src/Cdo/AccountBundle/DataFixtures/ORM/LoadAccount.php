<?php

namespace Cdo\AccountBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Cdo\AccountBundle\Entity\Account;

class LoadAccount extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $date = new \Datetime('now');
        
        $global_array = array(
            array(
                'title' => 'ABC Formation',
                'subdomain' => 'abcformation',
            ),
            array(
                'title' => 'Test Training',
                'subdomain' => 'test-training',
            ),
            array(
                'title' => 'GHI Conseil',
                'subdomain' => 'ghi_conseil',
            ),
        );
        
        foreach ($global_array as $account_array) {
            $account = new Account();
            $account->setCreatedAt($date);
            $account->setUpdatedAt($date);
            $account->setSubdomain($account_array['subdomain']);
            $account->setTitle($account_array['title']);
      
            $manager->persist($account);
        }
    
        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 0;
    }
}