<?php

namespace Cdo\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Cdo\BlogBundle\Entity\Page;

class LoadPage extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
                    'rank' => 0,
                    'level' => 0,
                    'title' => 'Page d\'accueil',
                    'slug' => 'Bienvenue !',
                    'content' => 'Bienvenue sur le site d\'ABC Formation !',
                    'parent' => null,
                ),
                array(
                    'rank' => 1,
                    'level' => 0,
                    'title' => 'Activité',
                    'slug' => 'activite',
                    'content' => 'ABC Formation est un organisme de formation.',
                    'parent' => null,
                ),
                array(
                    'rank' => 2,
                    'level' => 0,
                    'title' => 'Qui sommes-nous',
                    'slug' => 'qui-sommes-nous',
                    'content' => 'Nous sommes un organisme de formation.',
                    'parent' => null,
                ),
                array(
                    'rank' => 3,
                    'level' => 1,
                    'title' => 'L\'équipe',
                    'slug' => 'l-equipe',
                    'content' => 'Plusieurs experts de la formation.',
                    'parent' => 2,
                ),
                array(
                    'rank' => 4,
                    'level' => 0,
                    'title' => 'Coaching',
                    'slug' => 'coaching',
                    'content' => 'Nous coachons des équipes et des individus.',
                    'parent' => null,
                ),
            ),
            array(
                array(
                    'rank' => 0,
                    'level' => 0,
                    'title' => 'Accueil',
                    'slug' => 'accueil',
                    'content' => 'Bienvenue sur le site de Test Training',
                    'parent' => null,
                ),
                array(
                    'rank' => 0,
                    'level' => 0,
                    'title' => 'Qui sommes-nous',
                    'slug' => 'qui-sommes-nous',
                    'content' => 'Nous sommes un organisme de formation fictif.',
                    'parent' => null,
                ),
                array(
                    'rank' => 1,
                    'level' => 0,
                    'title' => 'Certifications',
                    'slug' => 'certifications',
                    'content' => 'Nous sommes certifiés Test Pro.',
                    'parent' => null,
                ),
                array(
                    'rank' => 2,
                    'level' => 0,
                    'title' => 'Contact',
                    'slug' => 'contact',
                    'content' => 'Nous sommes joignables 24h/24 par email.',
                    'parent' => null,
                ),
            ),
            array(
                array(
                    'rank' => 0,
                    'level' => 0,
                    'title' => 'La formation pour tous',
                    'slug' => 'la-formation-pour-tous',
                    'content' => 'Nous formons tout le monde.',
                    'parent' => null,
                ),
                array(
                    'rank' => 1,
                    'level' => 0,
                    'title' => 'L\'équipe',
                    'slug' => 'l-equipe',
                    'content' => 'Notre équipe est constituée de 5 experts.',
                    'parent' => null,
                ),
                array(
                    'rank' => 2,
                    'level' => 0,
                    'title' => 'Mentions légales',
                    'slug' => 'mentions-legales',
                    'content' => 'Notre site est hébergé par Training Dev.',
                    'parent' => null,
                ),
            ),
        );
        
        $i = 0;
        foreach ($global_array as $account_array) {
        	$j = 0;
            foreach ($account_array as $page_array) {
                $account = $entityManager->getRepository('CdoAccountBundle:Account')
                                         ->getNumbered($i);
                
                if ($account) {
                    $page = new Page();
                    $page->setCreatedAt($date);
                    $page->setUpdatedAt($date);
                    $page->setAccount($account);
                    $page->setRank($page_array['rank']);
                    $page->setLevel($page_array['level']);
                    $page->setTitle($page_array['title']);
                    $page->setSlug($page_array['slug']);
                    $page->setContent($page_array['content']);
                    if ($j == 0) {
                        $page->setHomepage(true);
                    }
                	if ($page_array['parent']) {
                		$manager->persist($page);
                		$manager->flush();
                		$page_parent = $entityManager->getRepository('CdoBlogBundle:Page')
	                                                 ->getByRank($account, $page_array['parent']);
	                    $page->setParent($page_parent);
//	                    $page->parent_id = $page_parent->getId();
                	}
                    
                    $manager->persist($page);
                    
                    // Doesn't word as commandline
//                    $this->container->get('cdo_site.twig.menu_extension')->encode($account->getSubdomain());
                }
                $j++;
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
        return 2;
    }
}