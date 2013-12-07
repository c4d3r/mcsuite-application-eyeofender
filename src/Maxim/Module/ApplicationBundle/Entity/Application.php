<?php

namespace Maxim\Module\ApplicationBundle\Entity;

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
    private $id;

    /**
     * @var Rank
     *
     * @ORM\ManyToOne(targetEntity="\Maxim\CMSBundle\Entity\Rank")
     * @ORM\JoinColumn(name="rank_id", referencedColumnName="id", nullable=false)
     */
    private $rank;

    /**
     * @var Website
     *
     * @ORM\ManyToOne(targetEntity="\Maxim\CMSBundle\Entity\Website")
     * @ORM\JoinColumn(name="website_id", referencedColumnName="id", nullable=false)
     */
    private $website;

    /**
     * @var string $name
     *
     * @ORM\Column(name="application_name", type="string", nullable=false)
     */
    private $name;

    /**
     * @var string $enabled
     *
     * @ORM\Column(name="application_enabled", type="integer", nullable=false)
     */
    private $enabled = 0;

    /**
     * @var array $fields
     *
     * @ORM\Column(name="application_fields", type="json_array", nullable=true)
     */
    private $fields;

    public function __construct()
    {
        $this->date = new \DateTime("now");
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

}
