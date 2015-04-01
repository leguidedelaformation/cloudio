<?php

namespace Cdo\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Cdo\BlogBundle\Entity\Post;

class LoadPost extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
                    'title' => 'La réforme de la formation pour les nuls',
                    'slug' => 'la-reforme-de-la-formation-pour-les-nuls',
                    'content' => '<p>Vous souhaitez en savoir plus sur la réforme ?</p><p>Voici une liste de liens vers des sites très complets.</p>',
                    'releasedate' => '-3 days',
                    'display' => true,
                    'user' => 0,
                    'categorys' => array(0, 1),
                ),
                array(
                    'title' => 'Faut-il rendre la formation obligatoire ?',
                    'slug' => 'faut-il-rendre-la-formation-obligatoire',
                    'content' => '<p>Le 0,9% a été supprimé.</p><p>Est-ce une bonne chose ?</p>',
                    'releasedate' => '-1 days',
                    'display' => true,
                    'user' => 0,
                    'categorys' => array(1, 2),
                ),
                array(
                    'title' => 'Mon avis sur la réforme de la formation',
                    'slug' => 'mon-avis-sur-la-reforme-de-la-formation',
                    'content' => '<p>Tout est allé un peu vite.</p><p>Quelques points pourraient être améliorés.</p>',
                    'releasedate' => '-2 days',
                    'display' => true,
                    'user' => 0,
                    'categorys' => array(1, 2),
                ),
                array(
                    'title' => 'Des infos en exclusivité sur les formations réglementaires',
                    'slug' => 'des-infos-en-exclusivite-sur-les-formations-reglementaires',
                    'content' => '<p>La règlementation est en train de changer.</p><p>Nous en savons désormais un peu plus.</p>',
                    'releasedate' => '-5 days',
                    'display' => true,
                    'user' => 0,
                    'categorys' => array(0),
                ),
                array(
                    'title' => 'Ce qu\'il faut savoir sur les nouveautés de la formation',
                    'slug' => 'ce-qu-il-faut-savoir-sur-les-nouveautes-de-la-formation',
                    'content' => '<p>Soyez les premiers informés des nouveautés du secteur.</p><p>Pour cela, lisez nos posts !</p>',
                    'releasedate' => '-2 days',
                    'display' => false,
                    'user' => 0,
                    'categorys' => array(0),
                ),
            ),
            array(
                array(
                    'title' => 'La crise expliquée par un expert',
                    'slug' => 'la-crise-expliquee-par-un-expert',
                    'content' => '<p>La crise est toujours là. Où en sommes-nous ?</p>',
                    'releasedate' => '-5 days',
                    'display' => true,
                    'user' => 0,
                    'categorys' => array(0),
                ),
                array(
                    'title' => 'Les capitales des pays les plus peuplés',
                    'slug' => 'les-capitales-des-pays-les-plus-peuples',
                    'content' => '<p>Les capitales des pays les plus peuplés ne sont pas forcément les villes les plus peuplées.</p>',
                    'releasedate' => '-2 days',
                    'display' => true,
                    'user' => 0,
                    'categorys' => array(0, 1),
                ),
                array(
                    'title' => 'Le règne de Louis XVI',
                    'slug' => 'le-regne-de-louis-xvi',
                    'content' => '<p>Les grandes lignes du règne de Louis XVI, avant la Révolution française.</p>',
                    'releasedate' => '-0 days',
                    'display' => false,
                    'user' => 0,
                    'categorys' => array(2),
                ),
            ),
            array(
                array(
                    'title' => 'Les nouvelles formations à la sécurité',
                    'slug' => 'les-nouvelles-formations-a-la-securite',
                    'content' => '<p>Les entreprises achètent de plus en plus de formations à la sécurité.</p>',
                    'releasedate' => '-2 days',
                    'display' => true,
                    'user' => 0,
                    'categorys' => array(1),
                ),
                array(
                    'title' => 'Est-il utile de se former à la santé et la sécurité au travail ?',
                    'slug' => 'est-il-utile-de-se-former-a-la-sante-et-la-securite-au-travail',
                    'content' => '<p>Les formations à la santé et sécurité au travail sont obligatoires depuis bien longtemps.</p>',
                    'releasedate' => '-1 days',
                    'display' => true,
                    'user' => 0,
                    'categorys' => array(0, 1),
                ),
                array(
                    'title' => 'La santé des collaborateurs est un facteur de compétitivité',
                    'slug' => 'la-sante-des-collaborateurs-est-un-facteur-de-competitivite',
                    'content' => '<p>Une entreprise est plus productive si ses salariés sont en bonne santé.</p>',
                    'releasedate' => '-1 days',
                    'display' => true,
                    'user' => 0,
                    'categorys' => array(0),
                ),
            ),
        );
        
        $i = 0;
        foreach ($global_array as $account_array) {
            foreach ($account_array as $post_array) {
                $account = $entityManager->getRepository('CdoAccountBundle:Account')
                                         ->getNumbered($i);
                
                if ($account) {
                    $post = new Post();
                    $post->setCreatedAt($date);
                    $post->setUpdatedAt($date);
                    $post->setAccount($account);
                    $post->setTitle($post_array['title']);
                    $post->setSlug($post_array['slug']);
                    $post->setContent($post_array['content']);
                    $post->setReleasedate(new \Datetime('now '.$post_array['releasedate']));
                    $post->setDisplay($post_array['display']);
                    $user = $entityManager->getRepository('CdoUserBundle:User')
                                          ->getNumbered($account, $post_array['user']);
                    $post->setUser($user);
                    foreach ($post_array['categorys'] as $j) {
	                    $category = $entityManager->getRepository('CdoBlogBundle:Category')
	                                              ->getNumbered($account, $j);
	                    $post->addCategory($category);
                    }
                    $manager->persist($post);
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
        return 4;
    }
}