<?php
/**
 * Created by IntelliJ IDEA.
 * User: Maxim
 * Date: 16/09/13
 * Time: 18:51
 * To change this template use File | Settings | File Templates.
 */

namespace Maxim\Module\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Maxim\CMSBundle\Entity\Announcement
 *
 * @ORM\Table(name="mcsf_subforum")
 * @ORM\Entity
 */
class SubForum
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @var integer $id
     */
    protected $id;

    /**
     * @var Forum
     *
     * @ORM\ManyToOne(targetEntity="Forum")
     * @ORM\JoinColumn(name="forum_id", referencedColumnName="id", nullable=true)
     */
    protected $forum;

    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string")
     */
    protected $title;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="string", nullable=false)
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
     * @ORM\Column(name="updatedOn", type="datetime", nullable=false)
     */
    protected $updatedOn;

    /**
     * @var string $sort
     *
     * @ORM\Column(name="sort", type="integer", nullable=false)
     */
    protected $sort;

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
     * @param \Maxim\Module\ForumBundle\Entity\Forum $forum
     */
    public function setForum($forum)
    {
        $this->forum = $forum;
    }

    /**
     * @return \Maxim\Module\ForumBundle\Entity\Forum
     */
    public function getForum()
    {
        return $this->forum;
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


}