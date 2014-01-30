<?php

namespace Maxim\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Maxim\CMSBundle\Entity\Purchase
 *
 * @ORM\Table(name="purchase")
 * @ORM\Entity(repositoryClass="Maxim\CMSBundle\Entity\PurchaseRepository")
 */
class Purchase
{
    const PURCHASE_COMPLETE  = "COMPLETED";
    const PURCHASE_PENDING   = "PENDING";
    const PURCHASE_FAILED    = "FAILED";
    const PURCHASE_INVALID   = "INVALID";

    const PURCHASE_ERROR_SQL = "ERROR_SQL";
    const PURCHASE_ERROR_COMMAND = "ERROR_COMMAND";
    const PURCHASE_ERROR_UNKNOWN = "ERROR_UNKNOWN";
    const PURCHASE_INVALID_AMOUNT = "INVALID_AMOUNT";

    const ITEM_DELIVERY_SUCCESS = "DELIVERED";
    const ITEM_DELIVERY_FAILED = "FAILED";
    const ITEM_DELIVERY_PENDING= "PENDING";

    const PAYMENT_METHOD_PAYPAL = "PAYPAL";
    const PAYMENT_METHOD_BITPAY = "BITPAY";

    protected $id;

    protected $amount;

    protected $date;

    protected $storeItem;

    protected $user;

    protected $status;

    protected $name;

    protected $ip;

    protected $transaction;

    protected $currency;

    protected $method;

    protected $itemDelivery = self::ITEM_DELIVERY_PENDING;

    protected $discount = 0.00;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     * @return Purchase
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    
        return $this;
    }

    /**
     * Get amount
     *
     * @return integer 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Purchase
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }


    /**
     * @param $status
     * @throws \InvalidArgumentException
     */
    public function setStatus($status)
	 {
         //check if its a valid type

         //get types
         $reflect = new \ReflectionClass(get_class($this));

         if(!in_array($status, $reflect->getConstants())) {
             throw new \InvalidArgumentException("Invalid type specified for Entity Notification");
         }

         $this->status = $status;
	 }
	 
	/**
     * Get status
     *
     * @return String 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set name
     *
     * @param String $name
     * @return Purchase
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return String
     */
    public function getName()
    {
        return $this->name;
    }

	 /**
     * Set ip
     *
     * @param String $ip
     * @return Purchase
     */
     public function setIp($ip)
	 {
	 	$this->ip = $ip;
		
		return $this;
	 }
	 
	/**
     * Get ip
     *
     * @return String 
     */
    public function getIp()
    {
        return $this->ip;
    }
	
	/**
     * Set transaction
     *
     * @param String $transaction
     * @return Purchase
     */
     public function setTransaction($transaction)
	 {
	 	$this->transaction = $transaction;
		
		return $this;
	 }
	 
	/**
     * Get transaction
     *
     * @return String 
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param \Maxim\CMSBundle\Entity\Shop $storeItem
     */
    public function setStoreItem($storeItem)
    {
        $this->storeItem = $storeItem;
    }

    /**
     * @return \Maxim\CMSBundle\Entity\Shop
     */
    public function getStoreItem()
    {
        return $this->storeItem;
    }

    /**
     * @param mixed $itemDelivery
     */
    public function setItemDelivery($itemDelivery)
    {
        $this->itemDelivery = $itemDelivery;
    }

    /**
     * @return mixed
     */
    public function getItemDelivery()
    {
        return $this->itemDelivery;
    }

    /**
     * @param float $discount
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    /**
     * @return float
     */
    public function getDiscount()
    {
        return $this->discount;
    }

}