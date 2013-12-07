<?php
/**
 * Created by IntelliJ IDEA.
 * User: Maxim
 * Date: 16/09/13
 * Time: 18:50
 * To change this template use File | Settings | File Templates.
 */

namespace Maxim\Module\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Maxim\CMSBundle\Entity\Announcement
 *
 * @ORM\Table(name="mcsf_category")
 * @ORM\Entity
 */
class Category {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @var integer $id
     */
    protected $id;

    /**
     * @var Website
     *
     * @ORM\ManyToOne(targetEntity="\Maxim\CMSBundle\Entity\Website")
     * @ORM\JoinColumn(name="website_id", referencedColumnName="id", nullable=true)
     */
    protected $website;

    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", nullable=true)
     */
    protected $title;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="string", nullable=true)
     */
    protected $description;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="\Maxim\CMSBundle\Entity\User")
     * @ORM\JoinColumn(name="createdBy", referencedColumnName="id")
     */
    protected $createdBy;

    /**
     * @var datetime $createdOn
     *
     * @ORM\Column(name="createdOn", type="datetime", nullable=false)
     */
    protected $createdOn;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="\Maxim\CMSBundle\Entity\User")
     * @ORM\JoinColumn(name="updatedBy", referencedColumnName="id")
     */
    protected $updatedBy;

    /**
     * @var datetime $updatedOn
     *
     * @ORM\Column(name="updatedOn", type="datetime", nullable=true)
     */
    protected $updatedOn;

    /**
     * @var Forum
     *
     * @ORM\OneToMany(targetEntity="Forum", mappedBy="category")
     */
    protected $forums;

    /**
     * @var boolean $visible
     *
     * @ORM\Column(name="visible", type="boolean", nullable=false)
     */
    protected $visible = false;

    /**
     * @var string $sort
     *
     * @ORM\Column(name="sort", type="integer", nullable=false)
     */
    protected $sort = 0;

    public function __construct()
    {
        $this->setCreatedOn(new \DateTime("now"));
    }

    /**
     * @param \Maxim\Module\ForumBundle\Entity\User $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return \Maxim\Module\ForumBundle\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param \Maxim\Module\ForumBundle\Entity\datetime $createdOn
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
    }

    /**
     * @return \Maxim\Module\ForumBundle\Entity\datetime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

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
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param \Maxim\Module\ForumBundle\Entity\User $updatedBy
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;
    }

    /**
     * @return \Maxim\Module\ForumBundle\Entity\User
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * @param \Maxim\Module\ForumBundle\Entity\datetime $updatedOn
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;
    }

    /**
     * @return \Maxim\Module\ForumBundle\Entity\datetime
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * @param \Maxim\Module\ForumBundle\Entity\Website $website
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    }

    /**
     * @return \Maxim\Module\ForumBundle\Entity\Website
     */
    public function getWebsite()
    {
        return $this->website;
    }

    public function __toString() {
        return $this->website . " > " . $this->title;
    }

    /**
     * @param \Maxim\Module\ForumBundle\Entity\Forum $forums
     */
    public function setForums($forums)
    {
        $this->forums = $forums;
    }

    /**
     * @return \Maxim\Module\ForumBundle\Entity\Forum
     */
    public function getForums()
    {
        return $this->forums;
    }

    /**
     * @param boolean $showOnHome
     */
    public function setShowOnHome($showOnHome)
    {
        $this->showOnHome = $showOnHome;
    }

    /**
     * @return boolean
     */
    public function getShowOnHome()
    {
        return $this->showOnHome;
    }

    /**
     * @param boolean $visible
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;
    }

    /**
     * @return boolean
     */
    public function getVisible()
    {
        return $this->visible;
    }
}