<?php

namespace Cdo\BlogBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Doctrine\ORM\EntityManager;
use Cdo\BlogBundle\Entity\Category;

class CategoryPostEvent extends Event
{
    protected $user;
    protected $category;
    protected $entityManager;
    
    public function __construct($securityContext, Category $category, EntityManager $em)
    {
        $this->user = $securityContext->getToken()->getUser();
        $this->category = $category;
        $this->entityManager = $em;
    }
    
    public function checkSlug()
    {
        $category = $this->category;
        $em = $this->entityManager;
        $category_rep = $em->getRepository('CdoBlogBundle:Category');
        
        $account = $category->getAccount();
        $slug_init = $category->getSlug();
        
        $category_slug = $category_rep->countSlug($account, $slug_init);
        if ($category_slug > 1) {
        	$loop = true;
        	$i = 0;
        	while ($loop == true) {
                $i++;
                $slug = $slug_init.'-'.$i;
                $category_slug = $category_rep->countSlug($account, $slug);
                $loop = ($category_slug > 0);
        	}
        	$category->setSlug($slug);
        	$em->flush();
        }
        
        return $category;
    }
}