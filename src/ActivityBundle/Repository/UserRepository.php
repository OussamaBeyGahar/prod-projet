<?php
/**
 * Created by PhpStorm.
 * User: Oussama.beygahar
 * Date: 23/06/2019
 * Time: 2:49 PM
 */

namespace ActivityBundle\Repository;


class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function userscount(){
        $query=$this->getEntityManager()
            ->createQuery("SELECT count(u) AS cntu FROM AppBundle:User u");
        return $query->getResult();

    }
}