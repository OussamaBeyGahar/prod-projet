<?php

namespace ActivityBundle\Repository;

/**
 * ReportsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ReportsRepository extends \Doctrine\ORM\EntityRepository
{
    public function countreports(){
        $query=$this->getEntityManager()
            ->createQuery("select count(l) as sumrep from ActivityBundle:Reports l");
        return $query->getResult();
    }
}
