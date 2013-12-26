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

    /**
     * @var integer $id
     *
    *  @ORM\Column(name="purchaseId", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer $amount
     *
     * @ORM\Column(name="amount", type="integer", nullable=true)
     */
    private $amount;

    /**
     * @var \DateTime $date
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var StoreItem
     *
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\ManyToOne(targetEntity="StoreItem")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="store_item_id", referencedColumnName="id")
     * })
     */
    private $storeItem;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="purchases", cascade={"persist", "remove"}, fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
	
	/**
     * @var String $status
     *
     * @ORM\Column(name="status", type="text", nullable=false)
     */
	private $status;

    /**
     * @var String $name
     *
     * @ORM\Column(name="name", type="text", nullable=true)
     */
    private $name;
	
	/**
     * @var String $ip
     *
     * @ORM\Column(name="ip", type="text", nullable=true)
     */
	private $ip;

	/**
     * @var String $transaction
     *
     * @ORM\Column(name="transaction", type="text", nullable=true)
     */
	private $transaction;

    /**
     * @ORM\ManyToOne(targetEntity="Currency")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="currency_code", referencedColumnName="short")
     * })
     */
    private $currency;

    /**
     * @ORM\Column(name="payment_method", type="text", nullable=true)
     */
    private $method;

    /**
     * @ORM\Column(name="store_item_delivery", type="text", nullable=false)
     */
    private $itemDelivery = self::ITEM_DELIVERY_PENDING;

    /**
     * @var double $discount
     *
     * @ORM\Column(name="discount", type="decimal", precision=2, nullable=false)
     */
    private $discount = 0.00;

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