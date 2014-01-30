<?php
/**
 * Author: Maxim
 * Date: 23/10/13
 * Time: 20:02
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Entity;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findLatestUsers($amount)
    {
        $query = $this->getEntityManager()->createQuery(
            "SELECT u
            FROM MaximCMSBundle:User u
            ORDER BY u.createdAt DESC"
        );
        $query->setMaxResults($amount);
        return $query->getResult();
    }
}