<?php

namespace Maxim\Module\TicketBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * Ticket
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity
 */
class Ticket
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    protected $description;

    /**
     * @var \DateTime
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    protected $date;

    /**
     * @var boolean
     *
     * @ORM\Column(name="closed", type="boolean", nullable=false)
     */
    protected $closed = 0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="statusChangedOn", type="datetime", nullable=true)
     */
    protected $statuschangedon;

    /**
     * @var String
     *
     * @ORM\Column(name="status", type="text", nullable=true)
     */
    protected $status;

    /**
     * @var \Maxim\CMSBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Maxim\CMSBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sectionChangedBy", referencedColumnName="id")
     * })
     */
    protected $sectionchangedby;

    /**
     * @var TicketSection
     *
     * @ORM\ManyToOne(targetEntity="TicketSection")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="section_id", referencedColumnName="id")
     * })
     */
    protected $section;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Maxim\CMSBundle\Entity\User")
     */
    protected $user;

    /**
     * @var \Maxim\CMSBundle\Entity\Website
     *
     * @ORM\ManyToOne(targetEntity="\Maxim\CMSBundle\Entity\Website")
     * @ORM\JoinColumn(name="website_id", referencedColumnName="id", nullable=true)
     */
    protected $website;

    /**
     * @var TicketReply
     *
     * @ORM\OneToMany(targetEntity="TicketReply", mappedBy="ticket")
     */
    protected $replies;

    public function __construct()
    {
        $this->setDate(new \DateTime("now"));
        $this->setStatuschangedon(new \DateTIme("now"));
        $this->replies = new ArrayCollection();
    }
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
     * set id
     *
     * @param integer $id
     * @return Ticket
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Ticket
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Ticket
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
     * Set closed
     *
     * @param boolean $closed
     * @return Ticket
     */
    public function setClosed($closed)
    {
        $this->closed = $closed;
    
        return $this;
    }

    /**
     * Get closed
     *
     * @return boolean 
     */
    public function getClosed()
    {
        return $this->closed;
    }

    /**
     * Set statuschangedon
     *
     * @param \DateTime $statuschangedon
     * @return Ticket
     */
    public function setStatuschangedon($statuschangedon)
    {
        $this->statuschangedon = $statuschangedon;
    
        return $this;
    }

    /**
     * Get statuschangedon
     *
     * @return \DateTime 
     */
    public function getStatuschangedon()
    {
        return $this->statuschangedon;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return Ticket
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return boolean 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set sectionchangedby
     *
     * @param \Maxim\CMSBundle\Entity\User $sectionchangedby
     * @return Ticket
     */
    public function setSectionchangedby(\Maxim\CMSBundle\Entity\User $sectionchangedby = null)
    {
        $this->sectionchangedby = $sectionchangedby;
    
        return $this;
    }

    /**
     * Get sectionchangedby
     *
     * @return \Maxim\CMSBundle\Entity\User 
     */
    public function getSectionchangedby()
    {
        return $this->sectionchangedby;
    }

    /**
     * Set section
     *
     * @param TicketSection $section
     * @return Ticket
     */
    public function setSection(TicketSection $section = null)
    {
        $this->section = $section;
    
        return $this;
    }

    /**
     * Get section
     *
     * @return \Maxim\CMSBundle\Entity\TicketSection 
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @param \Maxim\Module\TicketBundle\Entity\Website $website
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    }

    /**
     * @return \Maxim\Module\TicketBundle\Entity\Website
     */
    public function getWebsite()
    {
        return $this->website;
    }

    function __toString()
    {
        return $this->id;
    }

    /**
     * @param \Maxim\Module\TicketBundle\Entity\TicketReply $replies
     */
    public function setReplies($replies)
    {
        $this->replies = $replies;
    }

    /**
     * @return \Maxim\Module\TicketBundle\Entity\TicketReply
     */
    public function getReplies()
    {
        return $this->replies;
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