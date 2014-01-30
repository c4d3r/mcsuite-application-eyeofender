<?php

namespace Maxim\Module\ApplicationBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * Maxim\Module\ApplicationBundle\Entity\Application
 *
 * @ORM\Table(name="application")
 * @ORM\Entity
 */
class Application
{
    const FIELD_NAME = "NAME";
    const FIELD_TYPE = "TYPE";

    protected $id;

    protected $group;

    protected $website;

    protected $name;

    protected $enabled = 0;

    protected $fields;

    protected $userApplications;

    public function __construct()
    {
        $this->date = new \DateTime("now");
        $this->userApplications = new ArrayCollection();
    }

    /**
     * @param string $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return string
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
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
     * @param \Maxim\CMSBundle\Entity\Group $group
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }

    /**
     * @return \Maxim\CMSBundle\Entity\Group
     */
    public function getGroup()
    {
        return $this->group;
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
     * @param array $fields
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    function __toString()
    {
        return $this->name;
    }

    /**
     * @param mixed $userApplications
     */
    public function setUserApplications($userApplications)
    {
        $this->userApplications = $userApplications;
    }

    /**
     * @return mixed
     */
    public function getUserApplications()
    {
        return $this->userApplications;
    }


}
