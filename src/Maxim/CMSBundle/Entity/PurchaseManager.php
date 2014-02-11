<?php
/**
 * Author: Maxim
 * Date: 14/12/13
 * Time: 15:50
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Entity;


use Doctrine\ORM\EntityManager;

class PurchaseManager
{
    protected $em;

    protected $repo;

    protected $class;

    public function __construct(EntityManager $em, $class)
    {
        $this->em = $em;
        $this->class = $class;
        $this->repo = $em->getRepository($class);
    }

    public function findLatestPurchases($amount)
    {
        return $this->repo->findLatestPurchases($amount);
    }

    public function findTotalAmountEarnedThisMonth()
    {
        return $this->repo->findTotalAmountEarnedThisMonth();
    }
    public function findPurchasesPerDay()
    {
        return $this->repo->findPurchasesPerDay();
    }
} 