<?php
/**
 * Author: Maxim
 * Date: 12/06/2014
 * Time: 20:47
 * Property of MCSuite
 */

namespace Maxim\Module\TicketBundle\Entity;

class TicketReply
{
    protected $id;

    protected $userTicket;

    protected $user;

    protected $createdOn;

    protected $text;



    public function __construct($ticket = NULL, $text = NULL, $user = NULL)
    {
        $this->userTicket = $ticket;
        $this->text = $text;
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
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
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