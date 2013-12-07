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
            ->createQuery("SELECT COUNT(p) as total_purchases, s.name as name, s.image as image, s.amount as amount, s.id as id, s.description as description FROM MaximCMSBundle:Purchase p INNER JOIN MaximCMSBundle:Shop s WITH p.shop = s.id WHERE s.website = '" . $this->websiteid . "' GROUP BY s ORDER BY amount DESC")
            ->setMaxResults($amount)
            ->getResult();
    }
}