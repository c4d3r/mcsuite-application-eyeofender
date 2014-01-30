<?php

namespace Maxim\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Maxim\CMSBundle\Entity\Uservote
 *
 * @ORM\Table(name="user_vote")
 * @ORM\Entity
 */
class UserVote
{

    protected $id;

    protected $site;

    protected $user;

    protected $votedOn;

    function __construct()
    {
        $this->votedOn = new \DateTime("now");
    }

    /**
     * @param int $site
     */
    public function setSite($site)
    {
        $this->site = $site;
    }

    /**
     * @return int
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param int $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }
}