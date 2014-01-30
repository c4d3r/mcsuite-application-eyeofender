<?php

namespace Maxim\Module\TicketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TicketReply
 *
 * @ORM\Table(name="ticket_reply")
 * @ORM\Entity
 */
class TicketReply
{

    private $id;

    private $text;

    private $date;

    private $ticket;

    private $user;


    public function __construct()
    {
        $this->setDate(new \DateTime("now"));
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
     * Set text
     *
     * @param string $text
     * @return TicketReply
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return TicketReply
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
     * Set ticket
     *
     * @param Ticket $ticket
     * @return TicketReply
     */
    public function setTicket(Ticket $ticket)
    {
        $this->ticket = $ticket;

        return $this;
    }

    /**
     * Get ticket
     *
     * @return \Maxim\CMSBundle\Entity\Ticket
     */
    public function getTicket()
    {
        return $this->ticket;
    }

    /**
     * Set user
     *
     * @param \Maxim\CMSBundle\Entity\User $user
     * @return TicketReply
     */
    public function setUser($user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Maxim\CMSBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    function __toString()
    {
        return "";
    }
}