<?php
/**
 * Author: Maxim
 * Date: 06/10/13
 * Time: 17:08
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Maxim\CMSBundle\Entity\UserFriend
 *
 * @ORM\Table(name="user_friend")
 * @ORM\Entity
 */
class UserFriend {

    protected $friend;

    protected $user;

    protected $addedOn;

    public function __construct() {
        $this->addedOn = new \DateTime("now");
    }

    /**
     * @param \DateTime $addedOn
     */
    public function setAddedOn($addedOn)
    {
        $this->addedOn = $addedOn;
    }

    /**
     * @return \DateTime
     */
    public function getAddedOn()
    {
        return $this->addedOn;
    }

    /**
     * @param \Maxim\CMSBundle\Entity\User $friend
     */
    public function setFriend($friend)
    {
        $this->friend = $friend;
    }

    /**
     * @return \Maxim\CMSBundle\Entity\User
     */
    public function getFriend()
    {
        return $this->friend;
    }

    /**
     * @param \Maxim\CMSBundle\Entity\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return \Maxim\CMSBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }



}