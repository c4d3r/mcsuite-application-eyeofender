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
    /**
     * @var integer $id
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var integer $siteid
     *
     * @ORM\ManyToOne(targetEntity="Vote")
     * @ORM\JoinColumn(name="vote_id", referencedColumnName="id", nullable=false)
     */
    protected $site;

    /**
     * @var integer $siteid
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;

    /**
     * @var \DateTime $votedOn
     *
     * @ORM\Column(name="votedOn", type="datetime")
     */
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