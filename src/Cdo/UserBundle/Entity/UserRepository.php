<?php

namespace Cdo\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Cdo\AccountBundle\Entity\Account;

class UserRepository extends EntityRepository
{
    // Use : DataFixtures
    public function getNumbered(Account $account, $i)
    {
        $qb = $this->createQueryBuilder('u')
                   ->where('u.account = :account')
                   ->setParameter('account', $account)
                   ->setFirstResult($i)
                   ->setMaxResults(1)
                   ;
        
        return $qb->getQuery()
                  ->getOneOrNullResult();
    }
}
