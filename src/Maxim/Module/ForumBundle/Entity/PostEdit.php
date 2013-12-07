<?php
/**
 * Author: Maxim
 * Date: 10/11/13
 * Time: 13:20
 * Property of MCSuite
 */

namespace Maxim\Module\ForumBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Maxim\CMSBundle\Entity\Post
 *
 * @ORM\Table(name="mcsf_post_edit")
 * @ORM\Entity
 */
class PostEdit
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
     * @var Post
     *
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="updates", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     */
    protected $post;

    /**
     * @var String $reason
     *
     * @ORM\Column(name="reason", type="text", nullable=false)
     */
    protected $reason;

    /**
     * @var \Maxim\CMSBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="\Maxim\CMSBundle\Entity\User", inversedBy="postedits", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="updatedBy", referencedColumnName="id")
     */
    protected $updatedBy;

    /**
     * @var \Datetime $updatedOn
     *
     * @ORM\Column(name="updatedOn", type="datetime", nullable=false)
     */
    protected $updatedOn;

    public function __construct() {
        $this->updatedOn = new \DateTime("now");
    }

    /**
     * @param \Maxim\Module\ForumBundle\Entity\Post $post
     */
    public function setPost($post)
    {
        $this->post = $post;
    }

    /**
     * @return \Maxim\Module\ForumBundle\Entity\Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param \Maxim\CMSBundle\Entity\User $updatedBy
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;
    }

    /**
     * @return \Maxim\CMSBundle\Entity\User
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * @param \Datetime $updatedOn
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;
    }

    /**
     * @return \Datetime
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * @param String $reason
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
    }

    /**
     * @return String
     */
    public function getReason()
    {
        return $this->reason;
    }


}