<?php
/**
 * Author: Maxim
 * Date: 01/07/2014
 * Time: 11:34
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Entity;


class Award
{
    private $id;

    private $name;

    private $description;

    private $createdOn;

    private $image;

    private $type;

    private $website;

    const TYPE_FORUM = "AWARD_FORUM";
    const TYPE_STORE = "AWARD_STORE";
    const TYPE_RANK = "AWARD_RANK";

    public function __construct($name = null, $description = null, $image = null)
    {
        $this->name = $name;
        $this->description = $description;
        $this->image = $image;

        $this->createdOn = new \DateTime("now");
    }


    public static function getTypeList()
    {
        return array(
            self::TYPE_FORUM  => 'Forum',
            self::TYPE_STORE   => 'Store',
            self::TYPE_RANK => 'Rank',
        );
    }

    /**
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * @param \DateTime $createdOn
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
    }

    /**
     * @return null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param null $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param null $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param mixed $website
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }


} 