<?php

namespace Cdo\BlogBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Cdo\AccountBundle\Entity\Account;
use Cdo\BlogBundle\Entity\Category;

class PostRepository extends EntityRepository
{
    public function getAll(Account $account)
    {
        $qb = $this->createQueryBuilder('p')
                   ->where('p.account = :account')
                   ->setParameter('account', $account)
                   ->orderBy('p.display', 'DESC')
                   ->addOrderBy('p.releasedate', 'DESC')
                   ;
        
        return $qb->getQuery()
                  ->getResult();
    }
    
    public function getBySlugDisplay(Account $account, $slug)
    {
        $qb = $this->createQueryBuilder('p')
                   ->where('p.account = :account')
                   ->setParameter('account', $account)
                   ->andWhere('p.display = :display')
                   ->setParameter('display', true)
                   ->andWhere('p.slug = :slug')
                   ->setParameter('slug', $slug)
                   ;
        
        return $qb->getQuery()
                  ->getOneOrNullResult();
    }
    
    public function getDisplay(Account $account)
    {
        $qb = $this->createQueryBuilder('p')
                   ->where('p.account = :account')
                   ->setParameter('account', $account)
                   ->andWhere('p.display = :display')
                   ->setParameter('display', true)
                   ->addOrderBy('p.releasedate', 'DESC')
                   ;
        
        return $qb->getQuery()
                  ->getResult();
    }
    
    public function getByCategoryDisplay(Account $account, Category $category)
    {
        $qb = $this->createQueryBuilder('p')
                   ->where('p.account = :account')
                   ->setParameter('account', $account)
                   ->innerJoin('p.categorys', 'c', 'WITH', 'c = :category')
                   ->addSelect('c')
                   ->setParameter('category', $category)
                   ->andWhere('p.display = :display')
                   ->setParameter('display', true)
                   ;
        
        return $qb->getQuery()
                  ->getResult();
    }
    
    public function countSlug(Account $account, $slug)
    {
        $qb = $this->createQueryBuilder('p')
                   ->where('p.account = :account')
                   ->setParameter('account', $account)
                   ->andWhere('p.slug = :slug')
                   ->setParameter('slug', $slug)
                   ->select('COUNT(p)')
                   ;
        
        return $qb->getQuery()
                  ->getSingleScalarResult();
    }
}
