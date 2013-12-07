<?php
/**
 * Created by IntelliJ IDEA.
 * User: Maxim
 * Date: 22/09/13
 * Time: 15:31
 * To change this template use File | Settings | File Templates.
 */

namespace Maxim\Module\ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Maxim\CMSBundle\Entity\PostLike
 *
 * @ORM\Table(name="mcsf_post_likes")
 * @ORM\Entity
 */
class PostLike {

    /**
     * @var Post
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="likes", fetch="EAGER")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id", nullable=false)
     */
    protected $post;

    /**
     * @var User
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="\Maxim\CMSBundle\Entity\User", fetch="EAGER")
     * @ORM\JoinColumn(name="likedBy", referencedColumnName="id", nullable=false)
     */
    protected $likedBy;

    /**
     * @var \DateTime $likedOn
     *
     * @ORM\Column(name="likedOn", type="datetime", nullable=false)
     */
    protected $likedOn;

    public function __construct() {
        $this->likedOn = new \DateTime("now");
    }

    /**
     * @param \Maxim\Module\ForumBundle\Entity\User $likedBy
     */
    public function setLikedBy($likedBy)
    {
        $this->likedBy = $likedBy;
    }

    /**
     * @return \Maxim\Module\ForumBundle\Entity\User
     */
    public function getLikedBy()
    {
        return $this->likedBy;
    }

    /**
     * @param \Maxim\Module\ForumBundle\Entity\datetime $likedOn
     */
    public function setLikedOn($likedOn)
    {
        $this->likedOn = $likedOn;
    }

    /**
     * @return \Maxim\Module\ForumBundle\Entity\datetime
     */
    public function getLikedOn()
    {
        return $this->likedOn;
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
}