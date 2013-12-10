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

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @var integer $id
     */
    protected $id;

    /**
     * @var Rank
     *
     * @ORM\ManyToOne(targetEntity="\Maxim\CMSBundle\Entity\Rank")
     * @ORM\JoinColumn(name="rank_id", referencedColumnName="id", nullable=false)
     */
    protected $rank;

    /**
     * @var Website
     *
     * @ORM\ManyToOne(targetEntity="\Maxim\CMSBundle\Entity\Website")
     * @ORM\JoinColumn(name="website_id", referencedColumnName="id", nullable=false)
     */
    protected $website;

    /**
     * @var string $name
     *
     * @ORM\Column(name="application_name", type="string", nullable=false)
     */
    protected $name;

    /**
     * @var string $enabled
     *
     * @ORM\Column(name="application_enabled", type="integer", nullable=false)
     */
    protected $enabled = 0;

    /**
     * @var array $fields
     *
     * @ORM\Column(name="application_fields", type="json_array", nullable=true)
     */
    protected $fields;

    /**
     * @ORM\OneToMany(targetEntity="UserApplication", mappedBy="application")
     */
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
     * @param \Maxim\CMSBundle\Entity\Rank $rank
     */
    public function setRank($rank)
    {
        $this->rank = $rank;
    }

    /**
     * @return \Maxim\CMSBundle\Entity\Rank
     */
    public function getRank()
    {
        return $this->rank;
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
