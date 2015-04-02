<?php

namespace Cdo\BlogBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Doctrine\ORM\EntityManager;
use Cdo\BlogBundle\Entity\Comment;

class CommentPostEvent extends Event
{
    protected $comment;
    protected $entityManager;
    
    public function __construct(Comment $comment, EntityManager $em)
    {
        $this->comment = $comment;
        $this->entityManager = $em;
    }
    
    public function updateNumberComments()
    {
        $comment = $this->comment;
        $em = $this->entityManager;
        $post = $comment->getPost();
        
        $comment_count = $em->getRepository('CdoBlogBundle:Comment')
                            ->countByPostDisplay($post);
        $post->setNumberComments($comment_count);
        
        return $em->flush();
    }
}