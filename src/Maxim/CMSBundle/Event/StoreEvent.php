<?php
/**
 * Created by IntelliJ IDEA.
 * User: Maxim
 * Date: 04/09/13
 * Time: 15:13
 * To change this template use File | Settings | File Templates.
 */

namespace Maxim\CMSBundle\Event;

use Maxim\CMSBundle\Entity\Purchase;
use Maxim\CMSBundle\Entity\StoreItem;
use Symfony\Component\EventDispatcher\Event;

class StoreEvent extends Event
{
    protected $username;
    protected $isValid = true;
    protected $errors = array();
    protected $terms = false;
    protected $captureToken = NULL;
    protected $custom = array();
    protected $purchase;

    public function __construct(Purchase $purchase)
    {
        $this->purchase = $purchase;
    }

    /**
     * @param \Maxim\CMSBundle\Entity\Purchase $purchase
     */
    public function setPurchase($purchase)
    {
        $this->purchase = $purchase;
    }

    /**
     * @return \Maxim\CMSBundle\Entity\Purchase
     */
    public function getPurchase()
    {
        return $this->purchase;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param boolean $isValid
     */
    public function setIsValid($isValid)
    {
        $this->isValid = $isValid;
    }

    /**
     * @return boolean
     */
    public function getIsValid()
    {
        return $this->isValid;
    }

    /**
     * @param array $errors
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param boolean $terms
     */
    public function setTerms($terms)
    {
        $this->terms = $terms;
    }

    /**
     * @return boolean
     */
    public function getTerms()
    {
        return $this->terms;
    }

    /**
     * @param null $captureToken
     */
    public function setCaptureToken($captureToken)
    {
        $this->captureToken = $captureToken;
    }

    /**
     * @return null
     */
    public function getCaptureToken()
    {
        return $this->captureToken;
    }

    /**
     * @param array $custom
     */
    public function setCustom($custom)
    {
        $this->custom = $custom;
    }

    /**
     * @return array
     */
    public function getCustom()
    {
        return $this->custom;
    }


}