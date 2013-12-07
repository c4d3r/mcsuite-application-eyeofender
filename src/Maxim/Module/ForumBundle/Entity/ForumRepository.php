<?php
/**
 * Author: Maxim
 * Date: 15/11/13
 * Time: 10:47
 * Property of MCSuite
 */

namespace Maxim\Module\ForumBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ForumRepository extends EntityRepository{
//By(array("showOnHome" => true));

    public function findNewsPosts($websiteid)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT f, t, p, c
                FROM MaximModuleForumBundle:Forum f
                INNER JOIN f.category c
                LEFT JOIN f.threads t
                LEFT JOIN t.posts p
                WHERE f.showOnHome = true AND c.website = :website'
            )
            ->setParameter("website", $websiteid)
            ->getResult();
    }
}