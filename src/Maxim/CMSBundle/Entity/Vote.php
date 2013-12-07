<?php

namespace Maxim\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Maxim\CMSBundle\Entity\Vote
 *
 * @ORM\Table(name="vote")
 * @ORM\Entity
 */
class Vote
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=true)
     */
    private $name;

    /**
     * @var string $link
     *
     * @ORM\Column(name="link", type="string", length=45, nullable=true)
     */
    private $link;

    /**
     * @var string $image
     *
     * @ORM\Column(name="image", type="string", length=45, nullable=true)
     */
    private $image;

    /**
     * @var integer $reset
     *
     * @ORM\Column(name="reset", type="integer", nullable=true)
     */
    private $reset;

    /**
     * @var string $website
     *
     * @ORM\Column(name="website", type="string", length=45, nullable=true)
     */
    private $website;

    /**
     * @var boolean $votifier
     *
     * @ORM\Column(name="votifier", type="boolean", nullable=true)
     */
    private $votifier;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->accountid = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Vote
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
     * Set link
     *
     * @param string $link
     * @return Vote
     */
    public function setLink($link)
    {
        $this->link = $link;
    
        return $this;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Vote
     */
    public function setImage($image)
    {
        $this->image = $image;
    
        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set reset
     *
     * @param integer $reset
     * @return Vote
     */
    public function setReset($reset)
    {
        $this->reset = $reset;
    
        return $this;
    }

    /**
     * Get reset
     *
     * @return integer 
     */
    public function getReset()
    {
        return $this->reset;
    }

    /**
     * Set website
     *
     * @param string $website
     * @return Vote
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    
        return $this;
    }

    /**
     * Get website
     *
     * @return string 
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set votifier
     *
     * @param boolean $votifier
     * @return Vote
     */
    public function setVotifier($votifier)
    {
        $this->votifier = $votifier;
    
        return $this;
    }

    /**
     * Get votifier
     *
     * @return boolean 
     */
    public function getVotifier()
    {
        return $this->votifier;
    }

    /**
     * @param \Maxim\CMSBundle\Entity\Server $server
     */
    public function setServer($server)
    {
        $this->server = $server;
    }

    /**
     * @return \Maxim\CMSBundle\Entity\Server
     */
    public function getServer()
    {
        return $this->server;
    }
}