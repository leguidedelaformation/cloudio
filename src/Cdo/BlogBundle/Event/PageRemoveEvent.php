<?php

namespace Cdo\BlogBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Doctrine\ORM\EntityManager;

class PageRemoveEvent extends Event
{
    protected $user;
    protected $entityManager;
    
    public function __construct($securityContext, EntityManager $em)
    {
        $this->user = $securityContext->getToken()->getUser();
        $this->entityManager = $em;
    }
    
    public function updateRanks()
    {
        $user = $this->user;
        $em = $this->entityManager;
        
        $account = $user->getAccount();
        $page_collection = $em->getRepository('CdoBlogBundle:Page')
                              ->getAllOrderedRank($account);
        
        $i = 0;
        foreach ($page_collection as $page_item)
        {
        	$page_item->setRank($i);
        	$i++;
        }
        
        return $em->flush();
    }
}