<?php

namespace Maxim\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Type;

/**
 * Maxim\CMSBundle\Entity\StoreItem
 *
 * @ORM\Table(name="store_item")
 * @ORM\Entity(repositoryClass="Maxim\CMSBundle\Entity\StoreItemRepository")
 */
class StoreItem
{
    const STORE_SQL = 'SQL';
    const STORE_COMMAND = 'COMMAND';

    /**
     * @var integer $id
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=true)
     */
    protected $name;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var string $amount
     *
     * @ORM\Column(name="amount", type="decimal", precision=2)
     */
    protected $amount = 0.00;

    /**
     * @var boolean $visible
     *
     * @ORM\Column(name="visible", type="boolean", nullable=false)
     */
    protected $visible = true;

    /**
     * @var string $command
     *
     * @ORM\Column(name="command", type="text", nullable=true)
     */
    protected $command;

    /**
     * @var string $image
     *
     * @ORM\Column(name="image", type="text", nullable=true)
     */
    protected $image;

	 /**
     * @var string $image
     *
     * @ORM\Column(name="reduction", type="integer", type="decimal", precision=2, nullable=false)
     */
    protected $reduction = 0.00;

    /**
     * @var string $priority
     *
     * @ORM\Column(name="priority", type="integer", nullable=true)
     */
    protected $priority;

    /**
     * @var Section
     *
     * @ORM\ManyToOne(targetEntity="StoreCategory")
     * @ORM\JoinColumn(name="store_category_id", referencedColumnName="id", nullable=false)
     */
    protected $storeCategory;

    /**
     * @var Website
     *
     * @ORM\ManyToOne(targetEntity="Website")
     * @ORM\JoinColumn(name="website_id", referencedColumnName="id", nullable=false)
     */
    protected $website;

    /**
     * @var string $type
     *
     * @ORM\Column(name="type", type="text", nullable=false)
     */
    protected $type;

    /**
     * @var string $tax
     *
     * @ORM\Column(name="tax", type="decimal", precision=2, nullable=false)
     */
    protected $tax = 0;

    /**
     * @var string $description
     *
     * @ORM\Column(name="sort", type="integer", nullable=false, length=10)
     */
    protected $sort = 0;

    /**
     * @var string $duration
     *
     * @ORM\Column(name="duration", type="integer", nullable=false, length=11)
     */
    protected $duration = 0;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public static function getTypeList()
    {
        return array(
            self::STORE_COMMAND => "COMMAND",
            self::STORE_SQL => "SQL"
        );
    }


    /**
     * Set name
     *
     * @param string $name
     * @return StoreItem
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
     * @return StoreItem
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
     * @return StoreItem
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
     * @return StoreItem
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
     * @return StoreItem
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
     * @return StoreItem
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
     * @return StoreItem
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
        if(!in_array($type, array(self::STORE_COMMAND, self::STORE_SQL))){
            throw new \InvalidArgumentException("Invalid type specified for entity StoreItem");
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

    /**
     * @param \Maxim\CMSBundle\Entity\Section $storeCategory
     */
    public function setStoreCategory($storeCategory)
    {
        $this->storeCategory = $storeCategory;
    }

    /**
     * @return \Maxim\CMSBundle\Entity\Section
     */
    public function getStoreCategory()
    {
        return $this->storeCategory;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @param string $sort
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
    }

    /**
     * @return string
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param string $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * @return string
     */
    public function getDuration()
    {
        return $this->duration;
    }

}