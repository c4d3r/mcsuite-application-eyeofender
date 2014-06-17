<?php
/**
 * Author: Maxim
 * Date: 13/06/2014
 * Time: 12:38
 * Property of MCSuite
 */

namespace Maxim\Module\TicketBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TicketHistory
 *
 * @ORM\Table(name="ticket_history")
 * @ORM\Entity
 */
class TicketHistory
{
    const TYPE_REPLY = "REPLY";
    const TYPE_CLOSED = "CLOSED";
    const TYPE_OPENED = "OPENED";
    const TYPE_NEW = "NEW";

    protected $id;

    protected $userTicket;

    protected $createdOn;

    protected $type;

    //User who made the action
    protected $user;

    function __construct($userTicket, $type, $user)
    {
        $this->userTicket = $userTicket;
        $this->type = $type;
        $this->user = $user;
        $this->createdOn = new \DateTime("now");
    }

    /**
     * @return mixed
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * @param mixed $createdOn
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getUserTicket()
    {
        return $this->userTicket;
    }

    /**
     * @param mixed $userTicket
     */
    public function setUserTicket($userTicket)
    {
        $this->userTicket = $userTicket;
    }


} 