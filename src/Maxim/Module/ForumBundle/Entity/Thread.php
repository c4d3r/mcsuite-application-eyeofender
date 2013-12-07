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
 * @ORM\Table(name="mcsf_thread")
 * @ORM\Entity(repositoryClass="ThreadRepository")
 */
class Thread {

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
     * @ORM\ManyToOne(targetEntity="Forum", inversedBy="threads", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="forum_id", referencedColumnName="id", nullable=false)
     */
    protected $forum;

    /**
     * @var string $locked
     *
     * @ORM\Column(name="locked", type="boolean", nullable=false)
     */
    protected $locked = false;

    /**
     * @var string $pinned
     *
     * @ORM\Column(name="pinned", type="boolean", nullable=false)
     */
    protected $pinned = false;

    /**
     * @var string $text
     *
     * @ORM\Column(name="text", type="text", nullable=true)
     */
    protected $text;

    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", nullable=true)
     */
    protected $title;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="\Maxim\CMSBundle\Entity\User", inversedBy="threads", cascade={"persist", "remove"}, fetch="EAGER")
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
     * @var Post
     *
     * @ORM\OneToMany(targetEntity="Post", mappedBy="thread", fetch="EXTRA_LAZY")
     */
    protected $posts;

    /**
     * @var ThreadEdit
     *
     * @ORM\OneToMany(targetEntity="ThreadEdit", mappedBy="thread", fetch="EXTRA_LAZY")
     */
    protected $updates;

    public function __construct() {
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
     * @param string $locked
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;
    }

    /**
     * @return string
     */
    public function isLocked()
    {
        return $this->locked;
    }

    /**
     * @param string $pinned
     */
    public function setPinned($pinned)
    {
        $this->pinned = $pinned;
    }

    /**
     * @return string
     */
    public function isPinned()
    {
        return $this->pinned;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
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
     * @param \Maxim\Module\ForumBundle\Entity\Post $posts
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;
    }

    /**
     * @return \Maxim\Module\ForumBundle\Entity\Post
     */
    public function getPosts()
    {
        return $this->posts;
    }

    public function getLastPost()
    {
        $lastPost = null;

        if(!isset($this->posts)) {
            return null;
        }

        foreach($this->posts as $post)
        {
            if($lastPost == null || ($post->getCreatedOn() >= $lastPost->getCreatedOn()))
            {
                $lastPost = $post;
            }
        }
        return $lastPost;
    }

    public function __toString() {
        if($this->id > 0) {
            return $this->forum->getCategory()->getTitle() . " > " . $this->forum->getTitle() . " > " . $this->title;
        }
        return "";

    }

    /**
     * @param \Maxim\Module\ForumBundle\Entity\ThreadEdit $updates
     */
    public function setUpdates($updates)
    {
        $this->updates = $updates;
    }

    /**
     * @return \Maxim\Module\ForumBundle\Entity\ThreadEdit
     */
    public function getUpdates()
    {
        return $this->updates;
    }


}