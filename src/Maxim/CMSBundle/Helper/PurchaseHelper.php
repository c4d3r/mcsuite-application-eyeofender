<?php

namespace Maxim\CMSBundle\Helper;

use Doctrine\ORM\EntityManager;
use Maxim\CMSBundle\Entity\Purchase;
use Maxim\CMSBundle\Entity\StoreItem;
use Maxim\CMSBundle\Entity\User;
use Monolog\Logger;

class PurchaseHelper {

    protected $doctrine;
    protected $logger;

    public function __construct(EntityManager $doctrine, Logger $logger)
    {
        $this->doctrine = $doctrine;
        $this->logger = $logger;
    }

    /**
     * @param $item
     * @param User $user
     * @param $amount
     * @param $discount
     * @param $status
     * @param $name
     * @param $ip
     * @param $transaction
     * @param $delivery
     * @return Purchase
     */
    public function createPurchase($item, User $user, $amount, $discount, $status, $name, $ip, $transaction, $delivery) {

        $em = $this->doctrine;
        try
        {
            $purchase = new Purchase();
            $purchase->setDate(new \DateTime("now"));
            $purchase->setStatus($status);
            $purchase->setIp($ip);
            $purchase->setUser($user);
            $purchase->setAmount($amount);
            $purchase->setName($name);
            $purchase->setStoreItem($item);
            $purchase->setTransaction($transaction);
            $purchase->setItemDelivery($delivery);
            $purchase->setDiscount($discount);
            $em->persist($purchase);
            $em->flush();

            $this->logger->info("[IPN]Purchase made for user: " . $name);
            return $purchase;
        }
        catch(\Exception $ex)
        {
            $logger = $this->logger;
            $logger->err('STORE: '.$ex->getMessage());
        }
    }

}