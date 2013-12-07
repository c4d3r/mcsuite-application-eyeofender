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
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @var integer $id
     */
    private $id;
    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text", nullable=true)
     */
    private $text;

    /**
     * @var \DateTime
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var \Ticket
     *
     * @ORM\ManyToOne(targetEntity="Ticket", inversedBy="replies")
     * @ORM\JoinColumn(name="ticket_id", referencedColumnName="id", unique=false)
     */
    private $ticket;

    /**
     * @var \User
     * @ORM\ManyToOne(targetEntity="Maxim\CMSBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
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
     * @param \Maxim\CMSBundle\Entity\Ticket $ticket
     * @return TicketReply
     */
    public function setTicket(\Maxim\Module\TicketBundle\Entity\Ticket $ticket)
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
    public function setUser(\Maxim\CMSBundle\Entity\User $user = null)
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
}