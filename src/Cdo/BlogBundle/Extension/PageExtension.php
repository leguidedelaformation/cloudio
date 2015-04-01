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

    public function pageListByLevel($subdomain)
    {
        $em = $this->doctrine->getManager();
        $account = $em->getRepository('CdoAccountBundle:Account')->findSubdomain($subdomain);
        $cdo_blog_page_level_max = $this->container->getParameter('cdo_blog_page_level_max');
        
        $page_level = array();
        for ($i = 0; $i < $cdo_blog_page_level_max; $i++)
        {
            $page_level[$i] = $em->getRepository('CdoBlogBundle:Page')
                                 ->getByLevelOrderedRank($account, $i, false);
        }
        
        $page_collection = new ArrayCollection();
        foreach ($page_level[0] as $page_0)
        {
        	$page_collection->add($page_0);
        	foreach ($page_level[1] as $page_1)
        	{
        	    if ($page_1->getParent() == $page_0)
        	    {
        	        $page_collection->add($page_1);
        	        foreach ($page_level[2] as $page_2)
        	        {
        	        	if ($page_2->getParent() == $page_1)
        	        	{
        	        		$page_collection->add($page_2);
        	        	}
        	        }
        	    }
        	}
        }
        
        return $page_collection;
    }
    
    public function getName()
    {
        return 'page_extension';
    }
}