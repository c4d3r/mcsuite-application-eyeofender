<?php
/**
 * An MCSuite product - Sixyce studios
 * File: Server.php
 * User: Maxim
 * Date: 15/02/13
 * Time: 16:49
 */

namespace Maxim\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Maxim\CMSBundle\Entity\News
 *
 * @ORM\Table(name="server")
 * @ORM\Entity
 */
class Server {

    protected $id;

    protected $name;

    protected $description;

    protected $image;

    protected $abbr;

    protected $website;

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
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
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $abbr
     */
    public function setAbbr($abbr)
    {
        $this->abbr = $abbr;
    }

    /**
     * @return string
     */
    public function getAbbr()
    {
        return $this->abbr;
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

    public function __toString() {
        return  $this->name;
    }

    /**
     * @param \Maxim\CMSBundle\Entity\Shop $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * @return \Maxim\CMSBundle\Entity\Shop
     */
    public function getItems()
    {
        return $this->items;
    }


}