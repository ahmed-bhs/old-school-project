<?php

namespace EcoleBundle\Repository;

/**
 * ProfRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProfRepository extends \Doctrine\ORM\EntityRepository
{public function getNb() {
 
        return $this->createQueryBuilder('l')
 
                        ->select('COUNT(l)')
 
                        ->getQuery()
 
                        ->getSingleScalarResult();
 
    }
}
