<?php
/**
 * Author: Maxim
 * Date: 12/12/13
 * Time: 16:45
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Entity;


use Doctrine\ORM\EntityRepository;

class StoreCategoryRepository extends EntityRepository
{
    public function findAllVisibleOrderedByName($websiteid)
    {
        $query = $this->getEntityManager()->createQuery(
            "SELECT c, w
            FROM MaximCMSBundle:StoreCategory c
            INNER JOIN c.website w
            WHERE w.id = :websiteid
            AND c.visible = true
            ORDER BY c.name ASC"
        )->setParameter("websiteid", $websiteid);
        $query->useResultCache(true, 3600, __METHOD__ . serialize($query->getParameters()));

        return $query->getResult();
    }
} 