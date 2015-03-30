<?php

namespace Cdo\BlogBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Cdo\BlogBundle\Entity\Page;
use Cdo\AccountBundle\Entity\Account;
use Knp\DoctrineBehaviors\ORM as ORMBehaviors;

class PageRepository extends EntityRepository
{
    use ORMBehaviors\Tree\Tree;
    
    public function getAllOrderedRank(Account $account)
    {
        $qb = $this->createQueryBuilder('p')
                   ->where('p.account = :account')
                   ->setParameter('account', $account)
                   ->orderBy('p.display', 'DESC')
                   ->addOrderBy('p.rank')
                   ;
        
        return $qb->getQuery()
                  ->getResult();
    }
    
    public function getBySlug(Account $account, $slug)
    {
        $qb = $this->createQueryBuilder('p')
                   ->where('p.account = :account')
                   ->setParameter('account', $account)
                   ->andWhere('p.slug = :slug')
                   ->setParameter('slug', $slug)
                   ;
        
        return $qb->getQuery()
                  ->getOneOrNullResult();
    }
    
    public function getByRank(Account $account, $rank)
    {
        $qb = $this->createQueryBuilder('p')
                   ->where('p.account = :account')
                   ->setParameter('account', $account)
                   ->andWhere('p.rank = :rank')
                   ->setParameter('rank', $rank)
                   ;
        
        return $qb->getQuery()
                  ->getOneOrNullResult();
    }
    
    public function getByHomepage(Account $account)
    {
        $qb = $this->createQueryBuilder('p')
                   ->where('p.account = :account')
                   ->setParameter('account', $account)
                   ->andWhere('p.homepage = :homepage')
                   ->setParameter('homepage', true)
                   ;
        
        return $qb->getQuery()
                  ->getOneOrNullResult();
    }
    
    public function getByLevelOrderedRank(Account $account, $level, $display_only)
    {
        $qb_0 = $this->createQueryBuilder('p')
                     ->where('p.account = :account')
                     ->setParameter('account', $account)
                     ->andWhere('p.level = :level')
                     ->setParameter('level', $level)
                     ->orderBy('p.display', 'DESC')
                     ->addOrderBy('p.rank')
                     ;
        
        $qb = $qb_0;
        if ($display_only)
        {
        	$qb = $qb_0->andWhere('p.display = :display')
        	           ->setParameter('display', true)
        	           ;
        }
        
        return $qb->getQuery()
                  ->getResult();
    }
    
    public function getAllButPage(Account $account, Page $page)
    {
        $qb = $this->createQueryBuilder('p')
                   ->where('p.account = :account')
                   ->setParameter('account', $account)
                   ->andWhere('p.id != :id')
                   ->setParameter('id', $page->getId())
                   ->orderBy('p.display', 'DESC')
                   ->addOrderBy('p.rank')
                   ;
        
        return $qb->getQuery()
                  ->getResult();
    }
    
    public function countAll(Account $account)
    {
        $qb = $this->createQueryBuilder('p')
                   ->where('p.account = :account')
                   ->setParameter('account', $account)
                   ->select('COUNT(p)')
                   ;
        
        return $qb->getQuery()
                  ->getSingleScalarResult();
    }
    
    public function getSuplevelForm($account_id, $level)
    {
        $qb = $this->createQueryBuilder('p')
                   ->join('p.account', 'a')
                   ->where('a.id = :account_id')
                   ->setParameter('account_id', $account_id)
                   ->andWhere('p.display = :display')
                   ->setParameter('display', true)
                   ->andWhere('p.level < :level')
                   ->setParameter('level', $level)
                   ->orderBy('p.level')
                   ->addOrderBy('p.rank')
                   ;
        
        return $qb;
    }

	public function getRootLevelNodes($account_id)
	{
        $qb = $this->createQueryBuilder('p')
                   ->join('p.account', 'a')
                   ->where('a.id = :account_id')
                   ->setParameter('account_id', $account_id)
                   ->andWhere('p.display = :display')
                   ->setParameter('display', true)
                   ->addOrderBy('p.rank')
                   ;
	
	    return $qb->andWhere($qb->expr()->eq('p.materializedPath', '?1'))
	              ->setParameter(1, '');
	}
    
    public function getSuplevelButPageForm($account_id, $page_id, $level)
    {
        $qb = $this->createQueryBuilder('p')
                   ->join('p.account', 'a')
                   ->where('a.id = :account_id')
                   ->setParameter('account_id', $account_id)
                   ->andWhere('p.id != :page_id')
                   ->setParameter('page_id', $page_id)
                   ->andWhere('p.display = :display')
                   ->setParameter('display', true)
                   ->andWhere('p.level < :level')
                   ->setParameter('level', $level)
                   ->orderBy('p.level')
                   ->orderBy('p.rank')
                   ;
        
        return $qb;
    }

	public function getRootLevelNodesButPage($account_id, $page_id)
	{
        $qb = $this->createQueryBuilder('p')
                   ->join('p.account', 'a')
                   ->where('a.id = :account_id')
                   ->setParameter('account_id', $account_id)
                   ->andWhere('p.id != :page_id')
                   ->setParameter('page_id', $page_id)
                   ->andWhere('p.display = :display')
                   ->setParameter('display', true)
                   ->addOrderBy('p.rank')
                   ;
	
	    return $qb->andWhere($qb->expr()->eq('p.materializedPath', '?1'))
	              ->setParameter(1, '');
	}
    
    public function hasChildrenDisplay(Page $page)
    {
        $qb = $this->createQueryBuilder('p')
                   ->join('p.parent', 'pp')
                   ->where('pp.id = :id')
                   ->setParameter('id', $page->getId())
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
