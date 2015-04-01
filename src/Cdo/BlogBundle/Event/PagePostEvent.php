<?php

namespace Cdo\BlogBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Doctrine\ORM\EntityManager;
use Cdo\BlogBundle\Entity\Page;

class PagePostEvent extends Event
{
    protected $user;
    protected $page;
    protected $entityManager;
    
    public function __construct($securityContext, Page $page, EntityManager $em)
    {
        $this->user = $securityContext->getToken()->getUser();
        $this->page = $page;
        $this->entityManager = $em;
    }
    
    public function setLevel()
    {
        $page = $this->page;
        $em = $this->entityManager;
        
    	if ($parent = $page->getParent())
    	{
    		$page->setLevel($parent->getLevel() + 1);
    	} else {
    		$page->setLevel(0);
    	}
        
        return $em->flush();
    }
    
    public function updateRanks()
    {
        $user = $this->user;
        $page = $this->page;
        $em = $this->entityManager;
        
        $account = $user->getAccount();
        $page_collection = $em->getRepository('CdoBlogBundle:Page')
                              ->getAllButPage($account, $page);
        
        $i = 0;
        foreach ($page_collection as $page_item)
        {
        	if ($i == $page->getRank())
        	{
        		$i++;
        	}
        	$page_item->setRank($i);
        	$i++;
        }
        
        return $em->flush();
    }
    
    public function checkSlug()
    {
        $page = $this->page;
        $em = $this->entityManager;
        $page_rep = $em->getRepository('CdoBlogBundle:Page');
        
        $account = $page->getAccount();
        $slug_init = $page->getSlug();
        
        $page_slug = $page_rep->countSlug($account, $slug_init);
        if ($page_slug > 1) {
        	$loop = true;
        	$i = 0;
        	while ($loop == true) {
                $i++;
                $slug = $slug_init.'-'.$i;
                $page_slug = $page_rep->countSlug($account, $slug);
                $loop = ($page_slug > 0);
        	}
        	$page->setSlug($slug);
        	$em->flush();
        }
        
        return $page;
    }
}