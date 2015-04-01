<?php

namespace Cdo\BlogBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Doctrine\ORM\EntityManager;
use Cdo\BlogBundle\Entity\Post;

class PostPostEvent extends Event
{
    protected $user;
    protected $post;
    protected $entityManager;
    
    public function __construct($securityContext, Post $post, EntityManager $em)
    {
        $this->user = $securityContext->getToken()->getUser();
        $this->post = $post;
        $this->entityManager = $em;
    }
    
    public function categoryDisplay()
    {
        $user = $this->user;
        $em = $this->entityManager;
        
        $account = $user->getAccount();
        $category_collection = $em->getRepository('CdoBlogBundle:Category')
                                  ->getAll($account);
        
        foreach ($category_collection as $category)
        {
            $post_collection = $em->getRepository('CdoBlogBundle:Post')
                                  ->getByCategoryDisplay($account, $category);
            if (empty($post_collection)) {
                $category->setDisplay(false);
            } else {
                $category->setDisplay(true);
            }
        }
        
        return $em->flush();
    }
    
    public function checkSlug()
    {
        $post = $this->post;
        $em = $this->entityManager;
        $post_rep = $em->getRepository('CdoBlogBundle:Post');
        
        $account = $post->getAccount();
        $slug_init = $post->getSlug();
        
        $post_slug = $post_rep->countSlug($account, $slug_init);
        if ($post_slug > 1) {
        	$loop = true;
        	$i = 0;
        	while ($loop == true) {
                $i++;
                $slug = $slug_init.'-'.$i;
                $post_slug = $post_rep->countSlug($account, $slug);
                $loop = ($post_slug > 0);
        	}
        	$post->setSlug($slug);
        	$em->flush();
        }
        
        return $post;
    }
}