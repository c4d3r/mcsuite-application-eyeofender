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
 * @ORM\Table(name="mcsf_thread_edit")
 * @ORM\Entity
 */
class ThreadEdit
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
     * @var Thread
     *
     * @ORM\ManyToOne(targetEntity="Thread", inversedBy="updates", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="thread_id", referencedColumnName="id")
     */
    protected $thread;

    /**
     * @var String $reason
     *
     * @ORM\Column(name="reason", type="text", nullable=false)
     */
    protected $reason;

    /**
     * @var \Maxim\CMSBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="\Maxim\CMSBundle\Entity\User", inversedBy="threadedits", cascade={"persist", "remove"})
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
     * @param \Maxim\Module\ForumBundle\Entity\Thread $thread
     */
    public function setThread($thread)
    {
        $this->thread = $thread;
    }

    /**
     * @return \Maxim\Module\ForumBundle\Entity\Thread
     */
    public function getThread()
    {
        return $this->thread;
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