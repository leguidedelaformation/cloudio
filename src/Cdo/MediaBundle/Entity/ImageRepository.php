<?php

namespace Cdo\MediaBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Cdo\AccountBundle\Entity\Account;
use Cdo\MediaBundle\Entity\Image;

class ImageRepository extends EntityRepository
{
    public function getAllOrderedAlt(Account $account)
    {
        $qb = $this->createQueryBuilder('i')
                   ->where('i.account = :account')
                   ->setParameter('account', $account)
                   ->orderBy('i.alt')
                   ;
        
        return $qb->getQuery()
                  ->getResult();
    }
    
    public function findNavbarlogo(Account $account)
    {
        $qb = $this->createQueryBuilder('i')
                   ->where('i.account = :account')
                   ->setParameter('account', $account)
                   ->andWhere('i.navbarlogo = TRUE')
                   ;
        
        return $qb->getQuery()
                  ->getOneOrNullResult();
    }
    
    public function findNavbarlogoOld(Image $image, Account $account)
    {
        $qb = $this->createQueryBuilder('i')
                   ->where('i.account = :account')
                   ->setParameter('account', $account)
                   ->andWhere('i.navbarlogo = TRUE')
                   ->andWhere('i != :image')
                   ->setParameter('image', $image)
                   ;
        
        return $qb->getQuery()
                  ->getOneOrNullResult();
    }
}
