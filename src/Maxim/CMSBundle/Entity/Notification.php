<?php
/**
 * Author: Maxim
 * Date: 11/10/13
 * Time: 22:10
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Exception\InvalidArgumentException;

/**
 * Maxim\CMSBundle\Entity\Notification
 *
 * @ORM\Table(name="notification")
 * @ORM\Entity
 */
class Notification {

    const TYPE_FRIENDREQUEST = "NOTIFICATION_FRIENDREQUEST";
    const TYPE_PM            = "NOTIFICATION_PM";
    const TYPE_PURCHASE      = "NOTIFICATION_PURCHASE";

    protected $id;

    protected $text;

    protected $createdOn;

    protected $readOn;

    protected $user;

    protected $type;

    public function __construct()
    {
        $this->createdOn = new \DateTime("now");
    }

    /**
     * @param \DateTime $createdOn
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \DateTime $readOn
     */
    public function setReadOn($readOn)
    {
        $this->readOn = $readOn;
    }

    /**
     * @return \DateTime
     */
    public function getReadOn()
    {
        return $this->readOn;
    }

    /**
     * @param String $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return String
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param String $type
     */
    public function setType($type)
    {
        //check if its a valid type

        //get types
        $reflect = new \ReflectionClass(get_class($this));

        if(!in_array($type, $reflect->getConstants())) {
            throw new InvalidArgumentException("Invalid type specified for Entity Notification");
        }

        $this->type = $type;
    }

    /**
     * @return String
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param \Maxim\CMSBundle\Entity\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return \Maxim\CMSBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}