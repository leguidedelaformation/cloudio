<?php

namespace Cdo\BlogBundle\Extension;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class PageExtension extends \Twig_Extension
{
    protected $container;
    protected $doctrine;
    
    public function __construct(Container $container, RegistryInterface $doctrine)
    {
        $this->container = $container;
        $this->doctrine = $doctrine;
    }

    public function pageListByLevel($subdomain, $display_only)
    {
        $em = $this->doctrine->getManager();
        $account = $em->getRepository('CdoAccountBundle:Account')->findSubdomain($subdomain);
//        $cdo_blog_page_level_max = $GLOBALS['kernel']->getContainer()->getParameter('cdo_blog_page_level_max');
        $cdo_blog_page_level_max = $this->container->getParameter('cdo_blog_page_level_max');
//        $cdo_blog_page_level_max = 3;
        
        $page_level = array();
        for ($i = 0; $i < $cdo_blog_page_level_max; $i++)
        {
            $page_level[$i] = $em->getRepository('CdoBlogBundle:Page')
                                 ->getByLevelOrderedRank($account, $i, $display_only);
        }
        
        $page_collection = new ArrayCollection();
        $page_array = array();
        foreach ($page_level[0] as $page_0)
        {
        	$page_collection->add($page_0);
        	$page_0_array = array();
        	foreach ($page_level[1] as $page_1)
        	{
        	    
        	    if ($page_1->getParentNode() == $page_0)
        	    {
        	        $page_collection->add($page_1);
                    $page_1_array = array();
        	        foreach ($page_level[2] as $page_2)
        	        {
        	        	if ($page_2->getParentNode() == $page_1)
        	        	{
        	        		$page_collection->add($page_2);
                            $page_1_array[] = array(
                                'title' => $page_2->getTitle(),
                                'slug' => $page_2->getSlug(),
                                'entity' => 'page',
                                'children' => array(),
                            );
        	        	}
        	        }
                    $page_0_array[] = array(
                        'title' => $page_1->getTitle(),
                        'slug' => $page_1->getSlug(),
                        'entity' => 'page',
                        'children' => $page_1_array,
                    );
        	    }
        	}
    	    $page_array[] = array(
                'title' => $page_0->getTitle(),
                'slug' => $page_0->getSlug(),
                'entity' => 'page',
                'children' => $page_0_array,
            );
        }
        
        return array(
            'page_collection' => $page_collection,
            'page_array' => $page_array,
        );
    }
    
    public function getName()
    {
        return 'page_extension';
    }
}