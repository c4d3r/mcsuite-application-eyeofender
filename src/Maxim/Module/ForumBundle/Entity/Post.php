<?php
/**
 * Created by IntelliJ IDEA.
 * User: Maxim
 * Date: 16/09/13
 * Time: 18:51
 * To change this template use File | Settings | File Templates.
 */

namespace Maxim\Module\ForumBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * Maxim\CMSBundle\Entity\Post
 *
 * @ORM\Table(name="mcsf_post")
 * @ORM\Entity
 */
class Post {

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
     * @ORM\ManyToOne(targetEntity="Thread", inversedBy="posts", fetch="EAGER")
     * @ORM\JoinColumn(name="thread_id", referencedColumnName="id", nullable=false)
     */
    protected $thread;

    /**
     * @var string $text
     *
     * @ORM\Column(name="text", type="text", nullable=false)
     */
    protected $text;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="\Maxim\CMSBundle\Entity\User", inversedBy="posts", cascade={"persist", "remove"}, fetch="EXTRA_LAZY")
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
     * @var PostLike
     *
     * @ORM\OneToMany(targetEntity="PostLike", mappedBy="post", fetch="EXTRA_LAZY")
     */
    protected $likes;

    /**
     * @var PostUpdate
     *
     * @ORM\OneToMany(targetEntity="PostEdit", mappedBy="post", fetch="EXTRA_LAZY")
     */
    protected $updates;

    public function __construct() {
        $this->setCreatedOn(new \DateTime("now"));
        $this->children = new ArrayCollection();
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
     * @param \Maxim\Module\ForumBundle\Entity\PostLike $likes
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;
    }

    /**
     * @return \Maxim\Module\ForumBundle\Entity\PostLike
     */
    public function getLikes()
    {
        return $this->likes;
    }

    public function addLike(PostLike $like) {
        $this->likes[] = $like;
    }

    public function likesHasUser($user) {
        foreach($this->likes as $like) {
            if($like->getLikedBy() == $user) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param \Maxim\Module\ForumBundle\Entity\PostUpdate $updates
     */
    public function setUpdates($updates)
    {
        $this->updates = $updates;
    }

    /**
     * @return \Maxim\Module\ForumBundle\Entity\PostUpdate
     */
    public function getUpdates()
    {
        return $this->updates;
    }

}