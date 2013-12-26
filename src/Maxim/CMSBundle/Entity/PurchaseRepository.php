<?php
/**
 * Author: Maxim
 * Date: 14/10/13
 * Time: 18:14
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class PurchaseRepository extends EntityRepository
{
    protected $websiteid;

    public function setWebsiteId($websiteid)
    {
        $this->websiteid = $websiteid;
    }

    public function findAllTopPurchases($amount)
    {
        return $this->getEntityManager()
            ->createQuery("SELECT COUNT(p) as total_purchases, s.name as name, s.image as image, s.amount as amount, s.id as id, s.description as description FROM MaximCMSBundle:Purchase p INNER JOIN MaximCMSBundle:StoreItem s WITH p.storeItem = s.id WHERE s.website = '" . $this->websiteid . "' AND p.status = :status GROUP BY s ORDER BY amount DESC")
            ->setParameter("status", Purchase::PURCHASE_COMPLETE)
            ->setMaxResults($amount)
            ->getResult();
    }

    public function findLatestPurchases($amount)
    {
        $query = $this->getEntityManager()->createQuery(
            "SELECT p, u, i
            FROM MaximCMSBundle:Purchase p
            JOIN p.user u
            JOIN p.storeItem i
            WHERE p.status = :status
            ORDER BY p.date DESC"
        );
        $query->setParameter("status", Purchase::PURCHASE_COMPLETE);
        $query->setMaxResults($amount);
        return $query->getResult();
    }
    public function findTotalAmountEarnedThisMonth()
    {
        $query = $this->getEntityManager()->createQuery(
            "SELECT SUM(p.amount) as amount
            FROM MaximCMSBundle:Purchase p
            JOIN p.user u
            JOIN p.storeItem i
            WHERE p.status = :status
            AND MONTH(p.date) = :month
            AND YEAR(p.date) = :year
            ORDER BY p.date DESC"
        );
        $t = date('d-m-Y');
        $query->setParameters(array(
            "status" => Purchase::PURCHASE_COMPLETE,
            "month"  => date("m",strtotime($t)),
            "year"   => date("Y",strtotime($t))
        ));
        return $query->getSingleResult();
    }
}