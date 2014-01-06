<?php
/**
 * User: Maxim
 * Date: 03/03/13
 * Time: 11:28
 */

namespace Maxim\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Maxim\CMSBundle\Entity\Visitor
 *
 * @ORM\Table(name="stat_visitor")
 * @ORM\Entity
 */
class Visitor {
    /**
     * @var string $ip
     *
     * @ORM\Id
     * @ORM\Column(name="ip", type="string", length=39, nullable=false)
     */
    protected $ip;

    /**
     * @var string $time
     * @ORM\Id
     * @ORM\Column(name="time", type="integer", nullable=false)
     */
    protected $time;

    /**
     * @var string $user_agent
     * @ORM\Id
     * @ORM\Column(name="user_agent", type="string", nullable=true)
     */
    protected $user_agent;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    protected $user;

    /**
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param string $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return string
     */
    public function getTime()
    {
        return $this->time;
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

    /**
     * @param string $user_agent
     */
    public function setUserAgent($user_agent)
    {
        $this->user_agent = $user_agent;
    }

    /**
     * @return string
     */
    public function getUserAgent()
    {
        return $this->user_agent;
    }



}