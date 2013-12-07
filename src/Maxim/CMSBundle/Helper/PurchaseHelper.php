<?php

namespace Maxim\CMSBundle\Helper;

use Doctrine\ORM\EntityManager;
use Maxim\CMSBundle\Entity\Purchase;
use Maxim\CMSBundle\Entity\Shop;
use Monolog\Logger;

class PurchaseHelper {

    protected $doctrine;
    protected $logger;

    public function __construct(EntityManager $doctrine, Logger $logger)
    {
        $this->doctrine = $doctrine;
        $this->logger = $logger;
    }

    public function createPurchase($initiator, Shop $item, $ipaddress, $status, $forusername) {

        $em = $this->doctrine;
        try
        {
            $purchase = new Purchase();
            $purchase->setDate(new \DateTime("now"));
            $purchase->setStatus($status);
            $purchase->setIp($ipaddress);
            $purchase->setUser($initiator);
            $purchase->setShop($item);
            $purchase->setName($forusername);
            $em->persist($purchase);
            $em->flush();

            $this->logger->info("[IPN]Purchase made for user: " . $initiator->getUsername());
            return $purchase;
        }
        catch(\Exception $ex)
        {
            $logger = $this->logger;
            $logger->err('SHOP: '.$ex->getMessage());
        }
    }

}