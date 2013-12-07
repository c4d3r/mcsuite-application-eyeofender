<?php
/**
 * Created by IntelliJ IDEA.
 * User: Maxim
 * Date: 04/09/13
 * Time: 16:11
 * To change this template use File | Settings | File Templates.
 */

namespace Maxim\CMSBundle\Event;

use Maxim\CMSBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class UserEvent extends Event{

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
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