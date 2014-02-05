<?php
/**
 * Created by IntelliJ IDEA.
 * User: Maxim
 * Date: 16/09/13
 * Time: 18:50
 * To change this template use File | Settings | File Templates.
 */

namespace Maxim\Module\ForumBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

class Thread
{
    const THREAD_STATE_VISIBLE = "visible";
    const THREAD_STATE_DELETED = "deleted";

    protected $id;

    protected $forum;

    protected $locked = false;

    protected $pinned = false;

    protected $text;

    protected $title;

    protected $createdBy;

    protected $createdOn;

    protected $lastPost;

    protected $lastPostCreator;

    protected $state;

    protected $posts;

    protected $updates;

    protected $postCount = 0;

    protected $viewCount = 0;

    public function __construct($createdBy = NULL, $forum = NULL)
    {
        $this->setCreatedOn(new \DateTime("now"));
        $this->posts = new ArrayCollection();
        $this->state = self::THREAD_STATE_VISIBLE;

        $this->createdBy = $createdBy;
        $this->forum = $forum;
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

    /**
     * @param mixed $lastPost
     */
    public function setLastPost($lastPost)
    {
        $this->lastPost = $lastPost;
    }

    /**
     * @return mixed
     */
    public function getLastPost()
    {
        return $this->lastPost;
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

    /**
     * @param mixed $lastPostCreator
     */
    public function setLastPostCreator($lastPostCreator)
    {
        $this->lastPostCreator = $lastPostCreator;
    }

    /**
     * @return mixed
     */
    public function getLastPostCreator()
    {
        return $this->lastPostCreator;
    }

    /**
     * @param mixed $threadState
     */
    public function setState($threadState)
    {
        //get types
        $reflect = new \ReflectionClass(get_class($this));

        if(!in_array($threadState, $reflect->getConstants())) {
            throw new \InvalidArgumentException("Invalid type specified for Entity Thread");
        }

        $this->threadState = $threadState;

    }

    public function getState()
    {
        return $this->state;
    }

    /**
     * @param int $postCount
     */
    public function setPostCount($postCount)
    {
        $this->postCount = $postCount;
    }

    /**
     * @return int
     */
    public function getPostCount()
    {
        return $this->postCount;
    }

    /**
     * @param int $viewCount
     */
    public function setViewCount($viewCount)
    {
        $this->viewCount = $viewCount;
    }

    /**
     * @return int
     */
    public function getViewCount()
    {
        return $this->viewCount;
    }

    public function addPostCount($amount)
    {
        $this->postCount += $amount;
    }

    public function addViewCount($amount)
    {
        $this->viewCount += $amount;
    }

}