<?php

namespace Cdo\BlogBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Cdo\AccountBundle\Entity\Account;
use Cdo\BlogBundle\Entity\Post;

class CommentRepository extends EntityRepository
{
    public function getAll(Account $account)
    {
        $qb = $this->createQueryBuilder('c')
                   ->where('c.account = :account')
                   ->setParameter('account', $account)
                   ->orderBy('c.createdAt', 'DESC')
                   ;
        
        return $qb->getQuery()
                  ->getResult();
    }
    
    public function getByPostDisplay(Post $post)
    {
        $qb = $this->createQueryBuilder('c')
                   ->where('c.post = :post')
                   ->setParameter('post', $post)
                   ->andWhere('c.status = :status')
                   ->setParameter('status', 1)
                   ->orderBy('c.createdAt')
                   ;
        
        return $qb->getQuery()
                  ->getResult();
    }
    
    public function countByPostDisplay(Post $post)
    {
        $qb = $this->createQueryBuilder('c')
                   ->where('c.post = :post')
                   ->setParameter('post', $post)
                   ->andWhere('c.status = :status')
                   ->setParameter('status', 1)
                   ->select('COUNT(c)')
                   ;
        
        return $qb->getQuery()
                  ->getSingleScalarResult();
    }
}
