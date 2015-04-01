<?php

namespace Cdo\BlogBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Cdo\AccountBundle\Entity\Account;
use Cdo\BlogBundle\Entity\Post;

class CategoryRepository extends EntityRepository
{
    public function getAll(Account $account)
    {
        $qb = $this->createQueryBuilder('c')
                   ->where('c.account = :account')
                   ->setParameter('account', $account)
                   ->orderBy('c.display', 'DESC')
                   ->orderBy('c.title')
                   ;
        
        return $qb->getQuery()
                  ->getResult();
    }
    
    public function getBySlugDisplay(Account $account, $slug)
    {
        $qb = $this->createQueryBuilder('c')
                   ->where('c.account = :account')
                   ->setParameter('account', $account)
                   ->andWhere('c.display = :display')
                   ->setParameter('display', true)
                   ->andWhere('c.slug = :slug')
                   ->setParameter('slug', $slug)
                   ;
        
        return $qb->getQuery()
                  ->getOneOrNullResult();
    }
    
    public function getByPostDisplay(Account $account, Post $post)
    {
        $qb = $this->createQueryBuilder('c')
                   ->where('c.account = :account')
                   ->setParameter('account', $account)
                   ->innerJoin('c.posts', 'p', 'WITH', 'p = :post')
                   ->addSelect('p')
                   ->setParameter('post', $post)
                   ->andWhere('c.display = :display')
                   ->setParameter('display', true)
                   ->orderBy('c.title')
                   ;
        
        return $qb->getQuery()
                  ->getResult();
    }
    
    public function getAllForm($account_id)
    {
        $qb = $this->createQueryBuilder('c')
                   ->join('c.account', 'a')
                   ->where('a.id = :account_id')
                   ->setParameter('account_id', $account_id)
                   ->orderBy('c.title')
                   ;
        
        return $qb;
    }
    
    // Use : DataFixtures
    public function getNumbered(Account $account, $i)
    {
        $qb = $this->createQueryBuilder('c')
                   ->where('c.account = :account')
                   ->setParameter('account', $account)
                   ->setFirstResult($i)
                   ->setMaxResults(1)
                   ;
        
        return $qb->getQuery()
                  ->getOneOrNullResult();
    }
    
    public function getDisplay(Account $account)
    {
        $qb = $this->createQueryBuilder('c')
                   ->where('c.account = :account')
                   ->setParameter('account', $account)
                   ->andWhere('c.display = :display')
                   ->setParameter('display', true)
                   ->orderBy('c.title')
                   ;
        
        return $qb->getQuery()
                  ->getResult();
    }
    
    public function countSlug(Account $account, $slug)
    {
        $qb = $this->createQueryBuilder('c')
                   ->where('c.account = :account')
                   ->setParameter('account', $account)
                   ->andWhere('c.slug = :slug')
                   ->setParameter('slug', $slug)
                   ->select('COUNT(c)')
                   ;
        
        return $qb->getQuery()
                  ->getSingleScalarResult();
    }
}
