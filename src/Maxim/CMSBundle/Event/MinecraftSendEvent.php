<?php
/**
 * Created by IntelliJ IDEA.
 * User: Maxim
 * Date: 04/09/13
 * Time: 20:39
 * To change this template use File | Settings | File Templates.
 */

namespace Maxim\CMSBundle\Event;

use Maxim\CMSBundle\Entity\Purchase;
use Symfony\Component\EventDispatcher\Event;

class MinecraftSendEvent extends Event
{
    /**
     * @var array Purchase
     */
    protected $purchases;

    public function __construct($purchases = array())
    {
        $this->$purchases = $purchases;
    }

    /**
     * @param array $purchases
     */
    public function setPurchases($purchases)
    {
        $this->purchases = $purchases;
    }

    /**
     * @return array
     */
    public function getPurchases()
    {
        return $this->purchases;
    }

    public function addPurchase(Purchase $purchase)
    {
        return $this->purchases[] = $purchase;
    }
}