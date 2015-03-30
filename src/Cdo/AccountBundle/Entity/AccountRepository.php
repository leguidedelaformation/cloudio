<?php

namespace Cdo\AccountBundle\Entity;

use Doctrine\ORM\EntityRepository;

class AccountRepository extends EntityRepository
{
    // Use : DataFixtures
    public function getNumbered($i)
    {
        $qb = $this->createQueryBuilder('a')
                   ->setFirstResult($i)
                   ->setMaxResults(1)
                   ;
        
        return $qb->getQuery()
                  ->getOneOrNullResult();
    }
    
    public function findSubdomain($subdomain)
    {
        $qb = $this->createQueryBuilder('a')
                   ->where('a.subdomain = :subdomain')
                   ->setParameter('subdomain', $subdomain)
                   ->setMaxResults(1)
                   ;
        
        return $qb->getQuery()
                  ->getOneOrNullResult();
    }
}
