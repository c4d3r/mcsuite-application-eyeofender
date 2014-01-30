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

    private $id;

    private $name;

    private $createdon;

    private $groups;

    private $createdby;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->group = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add group
     *
     * @param \Maxim\CMSBundle\Entity\Group $group
     * @return TicketSection
     */
    public function addGroup(\Maxim\CMSBundle\Entity\Group $group)
    {
        $this->groups[] = $group;
    
        return $this;
    }

    /**
     * Remove group
     *
     * @param \Maxim\CMSBundle\Entity\Group $group
     */
    public function removeGroup(\Maxim\CMSBundle\Entity\Group $group)
    {
        $this->groups->removeElement($group);
    }

    /**
     * Get group
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGroups()
    {
        return $this->groups;
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