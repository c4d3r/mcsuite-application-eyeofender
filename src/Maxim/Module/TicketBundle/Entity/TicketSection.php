<?php

namespace Maxim\Module\TicketBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TicketSection
 *
 * @ORM\Table(name="ticket_section")
 * @ORM\Entity
 */
class TicketSection
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=true)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdOn", type="datetime", nullable=true)
     */
    private $createdon;

    /**
     * @ORM\ManyToMany(targetEntity="Maxim\CMSBundle\Entity\Rank", mappedBy="sections", cascade={"persist"})
     */
    private $ranks;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="Maxim\CMSBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="createdBy", referencedColumnName="id")
     * })
     */
    private $createdby;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rank = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return TicketSection
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set createdon
     *
     * @param \DateTime $createdon
     * @return TicketSection
     */
    public function setCreatedon($createdon)
    {
        $this->createdon = $createdon;
    
        return $this;
    }

    /**
     * Get createdon
     *
     * @return \DateTime 
     */
    public function getCreatedon()
    {
        return $this->createdon;
    }

    /**
     * Add rank
     *
     * @param \Maxim\CMSBundle\Entity\Rank $rank
     * @return TicketSection
     */
    public function addRank(\Maxim\CMSBundle\Entity\Rank $rank)
    {
        $this->ranks[] = $rank;
    
        return $this;
    }

    /**
     * Remove rank
     *
     * @param \Maxim\CMSBundle\Entity\Rank $rank
     */
    public function removeRank(\Maxim\CMSBundle\Entity\Rank $rank)
    {
        $this->ranks->removeElement($rank);
    }

    /**
     * Get rank
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRanks()
    {
        return $this->ranks;
    }

    /**
     * Set createdby
     *
     * @param \Maxim\CMSBundle\Entity\User $createdby
     * @return TicketSection
     */
    public function setCreatedby(\Maxim\CMSBundle\Entity\User $createdby = null)
    {
        $this->createdby = $createdby;
    
        return $this;
    }

    /**
     * Get createdby
     *
     * @return \Maxim\CMSBundle\Entity\User 
     */
    public function getCreatedby()
    {
        return $this->createdby;
    }

    public function __toString()
    {
        return $this->name;
    }
}