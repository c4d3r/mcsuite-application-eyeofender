<?php
/**
 * Author: Maxim
 * Date: 04/11/13
 * Time: 17:29
 * Property of MCSuite
 */

namespace Maxim\Module\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Maxim\CMSBundle\Entity\ThreadActivity
 *
 * @ORM\Table(name="mcsf_threadactivity")
 * @ORM\Entity
 */
class ThreadActivity
{
    /**
     * @var Thread
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Thread", inversedBy="posts")
     * @ORM\JoinColumn(name="thread_id", referencedColumnName="id", nullable=false)
     */
    protected $thread;

    /**
     * @var User
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="\Maxim\CMSBundle\Entity\User")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
    protected $user;


    /**
     * @var \datetime $lastRead
     *
     * @ORM\Column(name="lastRead", type="datetime", nullable=false)
     */
    protected $lastRead;

    public function __construct()
    {
        $this->lastRead = new \DateTime("now");
    }

    /**
     * @param \datetime $lastRead
     */
    public function setLastRead($lastRead)
    {
        $this->lastRead = $lastRead;
    }

    /**
     * @return \datetime
     */
    public function getLastRead()
    {
        return $this->lastRead;
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
     * @param \Maxim\Module\ForumBundle\Entity\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return \Maxim\Module\ForumBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}