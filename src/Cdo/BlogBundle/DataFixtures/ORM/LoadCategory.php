<?php

namespace Cdo\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Cdo\BlogBundle\Entity\Category;

class LoadCategory extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
                array(
                    'title' => 'Actualités',
                    'slug' => 'actualites',
                    'content' => 'L\'actualité du centre de formation.',
                    'display' => true,
                    'parent' => null,
                ),
                array(
                    'title' => 'Points de vue',
                    'slug' => 'points-de-vue',
                    'content' => 'La rédaction prend position.',
                    'display' => true,
                    'parent' => null,
                ),
                array(
                    'title' => 'Politique',
                    'slug' => 'politique',
                    'content' => 'Discussions sur la politique.',
                    'display' => true,
                    'parent' => null,
                ),
                array(
                    'title' => 'Religion',
                    'slug' => 'religion',
                    'content' => null,
                    'display' => false,
                    'parent' => null,
                ),
                array(
                    'title' => 'Société',
                    'slug' => 'societe',
                    'content' => null,
                    'display' => true,
                    'parent' => null,
                ),
            ),
            array(
                array(
                    'title' => 'Histoire',
                    'slug' => 'histoire',
                    'content' => 'Les historiens prennent la parole.',
                    'display' => true,
                    'parent' => null,
                ),
                array(
                    'title' => 'Géographie',
                    'slug' => 'geographie',
                    'content' => 'Tout sur les pays, les continents, etc.',
                    'display' => true,
                    'parent' => null,
                ),
                array(
                    'title' => 'Langues',
                    'slug' => 'langues',
                    'content' => 'Français, anglais, allemand, espagnol.',
                    'display' => false,
                    'parent' => null,
                ),
                array(
                    'title' => 'Economie',
                    'slug' => 'economie',
                    'content' => null,
                    'display' => true,
                    'parent' => null,
                ),
            ),
            array(
                array(
                    'title' => 'Santé',
                    'slug' => 'sante',
                    'content' => 'L\'actualité de nos formations à la santé.',
                    'display' => true,
                    'parent' => null,
                ),
                array(
                    'title' => 'Séminaires',
                    'slug' => 'seminaires',
                    'content' => 'La programmation de nos séminaires.',
                    'display' => false,
                    'parent' => null,
                ),
                array(
                    'title' => 'Sécurité',
                    'slug' => 'securite',
                    'content' => 'Evolutions réglementaires.',
                    'display' => true,
                    'parent' => null,
                ),
            ),
        );
        
        $i = 0;
        foreach ($global_array as $account_array) {
            foreach ($account_array as $category_array) {
                $account = $entityManager->getRepository('CdoAccountBundle:Account')
                                         ->getNumbered($i);
                
                if ($account) {
                    $category = new Category();
                    $category->setCreatedAt($date);
                    $category->setUpdatedAt($date);
                    $category->setAccount($account);
                    $category->setTitle($category_array['title']);
                    $category->setSlug($category_array['slug']);
                    $category->setContent($category_array['content']);
                    $category->setDisplay($category_array['display']);
//                	if ($category_array['parent']) {
//                		$manager->persist($category);
//                		$manager->flush();
//                		$category_parent = $entityManager->getRepository('CdoBlogBundle:Page')
//	                                                     ->getByRank($account, $category_array['parent']);
//	                    $category->setParent($category_parent);
//                	}
                    
                    $manager->persist($category);
                }
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
        return 3;
    }
}