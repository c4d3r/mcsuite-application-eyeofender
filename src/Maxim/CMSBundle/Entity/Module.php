<?php

namespace Maxim\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validation\Constraints AS Assert;
/**
 * Maxim\CMSBundle\Entity\Module
 *
 * @ORM\Table(name="core_module")
 * @ORM\Entity
 */
class Module
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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var \DateTime $date
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

	/**
     * @var integer $activated
     *
     * @ORM\Column(name="activated", type="integer", length=1, nullable=true)
     */
    private $activated;
	
	/**
     * @var string $version
     *
     * @ORM\Column(name="version", type="string", length=45, nullable=true)
     */
    private $version;

    /**
     * @var string $author
     *
     * @ORM\Column(name="author", type="string", length=45, nullable=true)
     */
    private $author;

	public function __construct()
	{

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
     * @return Page
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
     * Set description
     *
     * @param string $description
     * @return Description
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
     * @return Date
     */
    public function setCreatedOn($date = NULL)
    {
    	if($date == null)
		{
			$date = new \DateTime();
		}
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
     * Set activated
     *
     * @param integer $activated
     * @return Activated
     */
    public function setActivated($activated)
    {
        $this->activated = $activated;
    
        return $this;
    }

    /**
     * Get activated
     *
     * @return integer 
     */
    public function getActivated()
    {
        return $this->activated;
    }
	
	/**
     * Set version
     *
     * @param string $version
     * @return Modules
     */
    public function setVersion($version)
    {
        $this->version = $version;
    
        return $this;
    }

    /**
     * Get version
     *
     * @return string 
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set author
     *
     * @param string $author
     * @return Modules
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    
        return $this;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
    }
    
}