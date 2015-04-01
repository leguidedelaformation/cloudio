<?php

namespace Cdo\SiteBundle\Extension;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class MenuExtension extends \Twig_Extension
{
    protected $container;
    protected $doctrine;
    
    public function __construct(Container $container, RegistryInterface $doctrine)
    {
        $this->container = $container;
        $this->doctrine = $doctrine;
    }

    public function linkList($subdomain)
    {
        $em = $this->doctrine->getManager();
        $account = $em->getRepository('CdoAccountBundle:Account')->findSubdomain($subdomain);
        $cdo_blog_page_level_max = $this->container->getParameter('cdo_blog_page_level_max');
        
        $page_level = array();
        for ($i = 0; $i < $cdo_blog_page_level_max; $i++) {
            $page_level[$i] = $em->getRepository('CdoBlogBundle:Page')
                                 ->getByLevelOrderedRank($account, $i, true);
        }
        
        $link_array = array();
        $i = 0;
        foreach ($page_level[0] as $page_0) {
        	if ($i == 1) {
                $link_array[] = array(
                    'title' => 'Blog',
                    'type' => 'blog',
                );
        	}
        	$page_0_array = array();
        	foreach ($page_level[1] as $page_1) {
        	    if ($page_1->getParent() == $page_0) {
                    $page_1_array = array();
        	        foreach ($page_level[2] as $page_2) {
        	        	if ($page_2->getParent() == $page_1) {
                            $page_1_array[] = array(
                                'title' => $page_2->getTitle(),
                                'slug' => $page_2->getSlug(),
                                'type' => 'page',
                                'children' => array(),
                            );
        	        	}
        	        }
                    $page_0_array[] = array(
                        'title' => $page_1->getTitle(),
                        'slug' => $page_1->getSlug(),
                        'type' => 'page',
                        'children' => $page_1_array,
                    );
        	    }
        	}
    	    $link_array[] = array(
                'title' => $page_0->getTitle(),
                'slug' => $page_0->getSlug(),
                'type' => 'page',
                'children' => $page_0_array,
            );
            $i++;
        }
        
        return $link_array;
    }

    public function encode($subdomain)
    {
        $em = $this->doctrine->getManager();
        $account = $em->getRepository('CdoAccountBundle:Account')->findSubdomain($subdomain);
        $link_array = self::linkList($subdomain);
        
        $menu_encoded = json_encode($link_array, JSON_PRETTY_PRINT);
        
        $directory = $this->container->get('kernel')->getRootDir().'/../custom/'.$subdomain;

        if (!file_exists($directory)) {
            mkdir($directory, 0775, true);
        }
        $path = $directory.'/menu.json';
        $menu_file = fopen($path, 'w') or die("Unable to open file!");
        fwrite($menu_file, $menu_encoded);
        return fclose($menu_file);
    }
    
    public function getName()
    {
        return 'navbar_extension';
    }
}