<?php
/**
 * Author: Maxim
 * Date: 12/12/13
 * Time: 16:38
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Entity;


use Doctrine\ORM\EntityRepository;

class StoreItemRepository extends EntityRepository
{
    public function findAllVisibleOrderedByName($websiteid)
    {
        $query = $this->getEntityManager()->createQuery(
            "SELECT s, w, c
            FROM MaximCMSBundle:StoreItem s
            INNER JOIN s.storeCategory c
            INNER JOIN c.website w
            WHERE w.id = :websiteid
            AND c.visible = true
            AND s.visible = true
            ORDER BY s.name DESC"
        )->setParameter("websiteid", $websiteid);
        $query->useResultCache(true, 3600, __METHOD__ . serialize($query->getParameters()));

        return $query->getResult();
    }
} 