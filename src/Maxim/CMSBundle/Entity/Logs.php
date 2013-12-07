<?php

namespace Maxim\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Maxim\CMSBundle\Entity\Logs
 *
 * @ORM\Table(name="logs")
 * @ORM\Entity
 */
class Logs
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
     * @var integer $typeid
     *
     * @ORM\Column(name="typeId", type="integer", nullable=true)
     */
    private $typeid;

    /**
     * @var string $text
     *
     * @ORM\Column(name="text", type="text", nullable=true)
     */
    private $text;

    /**
     * @var \DateTime $date
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;



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
     * Set typeid
     *
     * @param integer $typeid
     * @return Logs
     */
    public function setTypeid($typeid)
    {
        $this->typeid = $typeid;
    
        return $this;
    }

    /**
     * Get typeid
     *
     * @return integer 
     */
    public function getTypeid()
    {
        return $this->typeid;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Logs
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
     * @return Logs
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
}