<?php

namespace Maxim\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Type;

/**
 * Maxim\CMSBundle\Entity\Shop
 *
 * @ORM\Table(name="shop")
 * @ORM\Entity
 */
class Shop
{
    const SHOP_SQL = 'SQL';
    const SHOP_COMMAND = 'COMMAND';

    /**
     * @var integer $id
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
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
     * @var string $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string $amount
     *
     * @ORM\Column(name="amount", type="float", precision=2)
     */
    private $amount;

    /**
     * @var boolean $visible
     *
     * @ORM\Column(name="visible", type="boolean", nullable=false)
     */
    private $visible = true;

    /**
     * @var string $command
     *
     * @ORM\Column(name="command", type="text", nullable=true)
     */
    private $command;

    /**
     * @var string $image
     *
     * @ORM\Column(name="image", type="text", nullable=true)
     */
    private $image;

	 /**
     * @var string $image
     *
     * @ORM\Column(name="reduction", type="integer", nullable=false)
     */
     private $reduction = 0;

    /**
     * @var string $priority
     *
     * @ORM\Column(name="priority", type="integer", nullable=true)
     */
    private $priority;

    /**
     * @var Section
     *
     * @ORM\ManyToOne(targetEntity="Section")
     */
    private $section;

    /**
     * @var Server
     *
     * @ORM\ManyToOne(targetEntity="Server", inversedBy="items")
     * @ORM\JoinColumn(name="server_id", referencedColumnName="id", nullable=false)
     */
    protected $server;

    /**
     * @var Website
     *
     * @ORM\ManyToOne(targetEntity="Website")
     * @ORM\JoinColumn(name="website_id", referencedColumnName="id", nullable=false)
     */
    private $website;

    /**
     * @var string $type
     *
     * @ORM\Column(name="type", type="text", nullable=false)
     */
    private $type;

    /**
     * @var string $tax
     *
     * @ORM\Column(name="tax", type="integer", nullable=false)
     */
    protected $tax = 0;

    /**
     * @param \Maxim\CMSBundle\Entity\Section $section
     */
    public function setSection($section)
    {
        $this->section = $section;
    }

    /**
     * @return \Maxim\CMSBundle\Entity\Section
     */
    public function getSection()
    {
        return $this->section;
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
     * @return Shop
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
     * @return Shop
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
     * Set amount
     *
     * @param string $amount
     * @return Shop
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    
        return $this;
    }

    /**
     * Get amount
     *
     * @return string 
     */
    public function getAmount()
    {
        return $this->amount;
    }
	
	/**
     * Set reduction
     *
     * @param string $reduction
     * @return Shop
     */
    public function setReduction($reduction)
    {
        $this->reduction = $reduction;
    
        return $this;
    }

    /**
     * Get reduction
     *
     * @return integer 
     */
    public function getReduction()
    {
        return $this->reduction;
    }
	
    /**
     * Set visible
     *
     * @param boolean $visible
     * @return Shop
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;
    
        return $this;
    }

    /**
     * Get visible
     *
     * @return boolean 
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Set command
     *
     * @param string $command
     * @return Shop
     */
    public function setCommand($command)
    {
        $this->command = $command;
    
        return $this;
    }

    /**
     * Get command
     *
     * @return string 
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Shop
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

    /**
     * @param string $priority
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /**
     * @return string
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param \Maxim\CMSBundle\Entity\Website $website
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    }

    /**
     * @return \Maxim\CMSBundle\Entity\Website
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        if(!in_array($type, array(self::SHOP_COMMAND, self::SHOP_SQL))){
            throw new \InvalidArgumentException("Invalid type specified for entity Shop");
        }
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $tax
     */
    public function setTax($tax)
    {
        $this->tax = $tax;
    }

    /**
     * @return string
     */
    public function getTax()
    {
        return $this->tax;
    }
}