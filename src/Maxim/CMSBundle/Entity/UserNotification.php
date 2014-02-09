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
class UserNotification
{
    const TYPE_FRIENDREQUEST = "NOTIFICATION_FRIENDREQUEST";
    const TYPE_PM            = "NOTIFICATION_PM";
    const TYPE_PURCHASE      = "NOTIFICATION_PURCHASE";

    protected $id;

    protected $createdOn;

    protected $readOn;

    protected $receiver;

    protected $user = null;

    protected $data;

    protected $website;

    protected $type;

    protected $link;

    public function __construct()
    {
        $this->createdOn    = new \DateTime("now");
        $this->data         = array();
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
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $readOn
     */
    public function setReadOn($readOn)
    {
        $this->readOn = $readOn;
    }

    /**
     * @return mixed
     */
    public function getReadOn()
    {
        return $this->readOn;
    }

    /**
     * @param mixed $receiver
     */
    public function setReceiver($receiver)
    {
        $this->receiver = $receiver;
    }

    /**
     * @return mixed
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * @param null $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $website
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    }

    /**
     * @return mixed
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param String $type
     * @throws InvalidArgumentException
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
     * @param mixed $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }

    public function addData($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function isRead()
    {
        return $this->readOn != null;
    }
}